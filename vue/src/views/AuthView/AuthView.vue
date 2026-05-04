<template>
    <div class="auth-view">
        <!-- Left Side: Info Section (Desktop only) -->
        <div class="auth-info-section d-none d-md-flex">
            <div class="info-overlay"></div>
            <div class="info-content">
                <div class="branding">
                    <img :src="logoSrc" @error="handleLogoError" height="64" class="mb-6" />
                    <h1 class="text-h2 font-weight-bold mb-4">mehar finance</h1>
                    <p class="text-h5 font-weight-light opacity-90">Advanced Human Capital Management Solution.</p>
                </div>
                <div class="features-list mt-12">
                    <div class="feature-item mb-8">
                        <v-icon color="primary" class="mr-4" size="32">mdi-check-decagram</v-icon>
                        <span>Comprehensive Employee Management</span>
                    </div>
                    <div class="feature-item mb-8">
                        <v-icon color="primary" class="mr-4" size="32">mdi-chart-areaspline</v-icon>
                        <span>Advanced Performance Analytics</span>
                    </div>
                    <div class="feature-item">
                        <v-icon color="primary" class="mr-4" size="32">mdi-security</v-icon>
                        <span>Secure & Compliant Infrastructure</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Form Section -->
        <div class="auth-form-section">
            <div class="auth-view-container">
                <img :src="logoSrc" @error="handleLogoError" height="40" alt="" aria-hidden="true" class="d-md-none mb-8" />
                <router-view v-slot="{ Component }" class="form-content">
                    <v-slide-x-transition hide-on-leave>
                        <component :is="Component" />
                    </v-slide-x-transition>
                </router-view>

                <div class="auth-footer">
                    <RouterLink
                        v-if="store.footerNavAction"
                        :to="{ name: store.footerNavAction.routeName }"
                        class="auth-footer-link"
                        >
                        {{ store.footerNavAction.label }}
                    </RouterLink>
                    <v-menu offset="16" v-if="languagesList.length > 1">
                        <template v-slot:activator="{ props, isActive }">
                            <MintButton
                                class="ms-auto"
                                v-bind="props"
                                variant="nav"
                                icon="mdi-translate"
                                :active="isActive"
                                :tooltip="languages.label('LBL_MINT4_AUTH_LANG_TOOLTIP')"
                            />
                        </template>
                        <MintMenuList :items="languagesList" />
                    </v-menu>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, onMounted } from 'vue'
import { useAuthViewStore } from './AuthViewStore'
import { useLanguagesStore } from '@/store/languages'
import MintButton from '@/components/MintButtons/MintButton.vue'
import MintMenuList, { MenuListItem } from '@/components/MintMenuList.vue'
import { usePreferencesStore } from '@/store/preferences'
import mintLogo from '@/assets/mint_logo.png'

const languages = useLanguagesStore()
const store = useAuthViewStore()
const preferences = usePreferencesStore()

const logoSrc = ref('legacy/custom/themes/default/images/company_logo.png');

function handleLogoError() {
    logoSrc.value = mintLogo;
}

const languagesList = computed<MenuListItem[]>(() => {
    const getFlagCode = (code: string) => {
        let [lang, country] = code.split('_')
        if (['ar', 'fa', 'he', 'ur', 'yi'].includes(lang.toLowerCase())) {
            country = 'arab'
        }
        return `fi-${country.toLowerCase()}`
    }
    return Object.entries(preferences.global?.languages ?? {}).map(([code, title]) => ({
        title: title?.toString() || '',
        icon: getFlagCode(code),
        onClick: () => {
            changeLanguage(code)
        },
    }))
})

onMounted(() => {
    const currentLang = localStorage.getItem('currentLang')
    if (!currentLang) {
        let browserLang = navigator.language
        const [languageCode, countryCode] = browserLang.split('-')
        let formattedbrowserLang = countryCode ? `${languageCode}_${countryCode}` : `${languageCode}_${languageCode.toUpperCase()}`

        const availableLanguages = Object.keys(preferences.global?.languages ?? {})

        let defaultLang = availableLanguages.find(lang => lang.toLowerCase() === formattedbrowserLang.toLowerCase())
        if (!defaultLang) {
            defaultLang = availableLanguages.find(lang => lang.toLowerCase().startsWith(languageCode.toLowerCase()))
        }

        if (!defaultLang) {
            defaultLang = "en_US"
        }
        changeLanguage(defaultLang)
    }
})

async function changeLanguage(lang = 'en_us') {
    localStorage.setItem('currentLang', lang)
    document.location.reload()
}
</script>

<style scoped lang="scss">
.auth-view {
    position: fixed;
    width: 100vw;
    height: 100vh;
    top: 0px;
    display: flex;
    overflow: hidden;
    background: rgb(var(--v-theme-background));
}

.auth-info-section {
    flex: 1.4;
    position: relative;
    background: url('@/assets/auth_bg.png') no-repeat center center;
    background-size: cover;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 80px;
    color: white;

    .info-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(var(--v-theme-primary), 0.8) 0%, rgba(var(--v-theme-secondary), 0.4) 100%);
        mix-blend-mode: multiply;
    }

    .info-content {
        position: relative;
        z-index: 1;
        max-width: 650px;

        .branding h1 {
            letter-spacing: -2px;
            text-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }

        .branding p {
            text-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .feature-item {
            display: flex;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 300;
            text-shadow: 0 1px 5px rgba(0,0,0,0.2);
        }
    }
}

.auth-form-section {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    background: rgb(var(--v-theme-surface));
    box-shadow: -10px 0 30px rgba(0,0,0,0.05);
    z-index: 2;
    overflow-y: auto;
}

.auth-view-container {
    width: 100%;
    max-width: 480px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    .form-content {
        width: 100%;
        margin-top: 24px;
        text-align: center;
    }

    .auth-footer {
        width: 100%;
        margin-top: 48px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        font-size: 14px;
        color: rgb(var(--v-theme-secondary));

        div {
            cursor: pointer;
            user-select: none;
            &:hover {
                color: rgb(var(--v-theme-secondary-dark));
            }
        }
    }
}

.auth-footer-link {
    color: rgb(var(--v-theme-primary));
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;

    &:hover {
        color: rgb(var(--v-theme-primary-dark));
    }
}
</style>

<style></style>
