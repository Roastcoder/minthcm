<?php


/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * mehar finance is a Human Capital Management software based on SuiteCRM developed by mehar finance, 
 * Copyright (C) 2018-2023 mehar finance
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by mehar finance" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by mehar finance".
 */

namespace mehar finance\Api\Controllers\OAuth2;

use Api\V8\OAuth2\Repository\ClientRepository;
use Api\V8\OAuth2\Repository\ScopeRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\ResourceServer;
use mehar finance\Api\Controllers\OAuth2\Grants\FrontendGrant;
use mehar finance\Api\Controllers\OAuth2\Grants\MobileGrant;
use mehar finance\Api\Entities\OAuth2\Client;
use mehar finance\Api\Entities\Users;
use mehar finance\Api\Repositories\OAuth2\AccessTokenRepository;
use mehar finance\Api\Repositories\OAuth2\RefreshTokenRepository;
use mehar finance\Api\Repositories\UsersRepository;

class Server
{
    const OS_WINDOWS = 'WINDOWS';
    const OS_LINUX = 'LINUX';
    const OS_OSX = 'OSX';

    const GRANT_INTERVAL = 'PT1H';
    const REFRESH_TOKEN_TTL = 'P1M';

    public static function getGrantInterval(): string
    {
        global $mint_config;
        return $mint_config['session_grant_interval'] ?? self::GRANT_INTERVAL;
    }

    const OAUTH2_PRIVATE_KEY = 'configs/private.key';
    const OAUTH2_PUBLIC_KEY = 'configs/public.key';

    public static function getAuthorizationServer(EntityManagerInterface $entityManager): AuthorizationServer
    {
        global $mint_config;

        $oauth2EncKey = $mint_config['oauth2_encryption_key'] ?? 'mehar finance-DEFKEY';

        /** @var ClientRepository */
        $oauth2_client_repository = $entityManager->getRepository(Client::class);
        $server = new AuthorizationServer(
            $oauth2_client_repository,
            new AccessTokenRepository($entityManager),
            new ScopeRepository(),
            new CryptKey(
                sprintf('file://%s/%s', $GLOBALS['BASE_DIR'], self::OAUTH2_PRIVATE_KEY),
                null,
                self::shouldCheckPermissions()
            ),
            $oauth2EncKey
        );

        $grantInterval = new \DateInterval(self::getGrantInterval());

        // Client credentials grant
        $server->enableGrantType(
            new ClientCredentialsGrant(),
            $grantInterval
        );

        /** @var UsersRepository */
        $user_repository = $entityManager->getRepository(Users::class);
        // Password credentials grant
        $server->enableGrantType(
            new PasswordGrant(
                $user_repository,
                new RefreshTokenRepository($entityManager)
            ),
            $grantInterval
        );

        // Mobile grant
        $server->enableGrantType(
            new MobileGrant(
                $user_repository,
                new RefreshTokenRepository($entityManager)
            ),
            $grantInterval
        );

        // Frontend grant
        $server->enableGrantType(
            new FrontendGrant(
                $user_repository,
                new RefreshTokenRepository($entityManager)
            ),
            $grantInterval
        );

        $refreshGrant = new RefreshTokenGrant(
            new RefreshTokenRepository($entityManager)
        );
        $refreshGrant->setRefreshTokenTTL(new \DateInterval(self::REFRESH_TOKEN_TTL));

        $server->enableGrantType(
            $refreshGrant,
            $grantInterval
        );

        return $server;
    }

    public static function getResourceServer(EntityManagerInterface $entityManager): ResourceServer
    {
        return new ResourceServer(
            new AccessTokenRepository($entityManager),
            new CryptKey(
                sprintf('file://%s/%s', $GLOBALS['BASE_DIR'], self::OAUTH2_PUBLIC_KEY),
                null,
                self::shouldCheckPermissions()
            ),
        );
    }

    private static function shouldCheckPermissions(): bool
    {
        $os = '';
        switch (true) {
            case stristr(PHP_OS, 'DAR'):
                $os = self::OS_OSX;

            case stristr(PHP_OS, 'WIN'):
                $os = self::OS_WINDOWS;

            case stristr(PHP_OS, 'LINUX'):
                $os = self::OS_LINUX;
        }

        return $os !== self::OS_WINDOWS;
    }
}
