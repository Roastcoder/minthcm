var mehar financePopup = function (title, body, buttons, options, onShow) {
    var classPopup = new mehar financePopupClass(title, body, buttons, options, onShow);
    return classPopup.init();
}
mehar financePopup.close = function () {
    var id = $( '.mehar financePopup' ).last().attr( 'id' );
    $('#' + id).remove();
};

class mehar financePopupClass {

    constructor(title, body, buttons, options, onShow, load_id) {
        this.id = load_id || Date.now();
        this.title = title || "";
        this.body = body || "";
        this.buttons = buttons || [];
        this.options = options || {};
        this.onShow = onShow || function () {};
    }

    init() {
        this.init = function () {
            if ( undefined !== $( '#' + this.id ).get( 0 ) ) {
                $( '#' + this.id ).remove();
            }
            $( 'body' ).append( '<div class="mehar financePopup" id="' + this.id + '">' + this.getBody() + '</div>' );
            setTimeout( function () { //special timeout. Javasript has to wait on finishing append. So, does not remove the TimeOut
                this.onShow();
            }.bind(this), 0 );
            if ( typeof this.options.noCloseButton !== 'undefined' && this.options.noCloseButton ) {
                $( '.mehar financePopup-header' ).removeClass( 'mehar financePopup-close' );
            } else {
                $( '#' + this.id + '_close' ).click( this.close );
            }
            if ( typeof this.options.css !== 'undefined' && this.options.css ) {
                $( '#' + this.id + '> .mehar financePopup-container' ).css( this.options.css );
            }
            this.setButtonsEvents();
        };

        this.getBody = function () {
            var body = _.template( '<div class="mehar financePopup-container"><div class="mehar financePopup-header mehar financePopup-close"><div class="mehar financePopup-title"><input id="<%= id %>_hidenId" type="hidden" value="<%= id %>"></input><%= title %></div><span id="<%=id %>_close" class="suitepicon suitepicon-action-clear"></span><div style="clear: both;"></div></div><div class="mehar financePopup-body"><%= body %></div><div class="mehar financePopup-buttons"><%= buttons %></div></div>' );
            return body( {
                title: this.title,
                body: this.body,
                buttons: this.getButtons(),
                id: this.id
            } );
        };

        this.getButtons = function () {
            const button = _.template( '<input type="button" accesskey="<%= accesskey %>" value="<%= text %>" id="<%= id %>" <% if (primary) { %> class="button primary" <% } %> />' );
            let buttonsLeft = '';
            let buttonsRight = '';
            this.buttons.forEach( function ( btn ) {
                const buttonHtml = button({
                    text: btn.text,
                    id: btn.id ?? btn.text.replace(' ', '_'),
                    primary: !!btn.primary,
                    accesskey: btn.accesskey
                });
                if (btn.left) {
                    buttonsLeft += buttonHtml
                } else {
                    buttonsRight += buttonHtml
                }
            });
            return `<div>${buttonsLeft}</div><div>${buttonsRight}</div>`;
        };

        this.setButtonsEvents = function () {
            const _this = this;
            $( '#' + this.id + ' .mehar financePopup-buttons input' ).each( function () {
                $( this ).click( _this.buttons.find(btn => btn.text === $(this).val())?.click );
            } );
        };



        this.init();
        return this;
    }
    close() {
        var new_id = this.id.replace( "_close", "_hidenId" );
        var id_close = document.getElementById( new_id ).value;
        $( '#' + id_close ).fadeOut( 400, function () {
            $( '#' + id_close ).remove();
        } );
    }
    ;

};


var showLoadingScreen = function (title, message) {
    var loading;
    if (message) {
        var body = "<div class='mehar financePopup-load'><img src='themes/default/images/loading.gif' alt='loading'></img><div class='mehar financePopup-load-message'><span>" + message + "</span></div></div>";
    }
    else {
        var body = "<div class='mehar financePopup-load'><img src='themes/default/images/loading.gif' alt='loading'></img></div>";
    }
    loading = new mehar financePopupClass(title, body, '', { noCloseButton: true }, '', 'load');
    loading.init();
    return loading;
};
var closeLoadingScreen = function () {
    $('#load').remove();
    return true;
};

mehar financePopup.confirm = function (body, options = {}) {
    return new Promise((resolve) => {
        mehar financePopup(
            viewTools.language.get('app_strings', 'LBL_CONFIRM'),
            body,
            [
                {
                    text: options.customLabels?.noBtn || viewTools.language.get('app_strings', 'LBL_NO'),
                    click: () => {
                        mehar financePopup.close();
                        resolve(false);
                    },
                    left: true,
                },
                {
                    text: options.customLabels?.yesBtn || viewTools.language.get('app_strings', 'LBL_YES'),
                    click: () => {
                        mehar financePopup.close();
                        resolve(true);
                    },
                    primary: true,
                },
            ],
            {
                noCloseButton: true,
                css: { whiteSpace: 'break-spaces', maxWidth: '500px' },
            },
        );
    });
}

mehar financePopup.alert = function (body, options = {}) {
    return new Promise((resolve) => {
        mehar financePopup(
            '',
            body,
            [
                {
                    text: options.customLabels?.confirmBtn || viewTools.language.get('app_strings', 'LBL_OK'),
                    click: () => {
                        mehar financePopup.close();
                        resolve(true);
                    },
                    primary: true,
                },
            ],
            {
                noCloseButton: true,
                css: { whiteSpace: 'break-spaces', maxWidth: '500px' },
            },
        );
    });
}
