/**
 * Author: Md. Muntasir Enam <mme.ctg@gmail.com>
 * Copyright: (c) 2015 Md. Muntasir Enam <mme.ctg@gmail.com>
 */
var ss_utility = function() {

    var mask_image_path = IMAGEPATH + 'loading.gif';
    var loading_image_path = IMAGEPATH + 'loading.gif';

    return {
        init: function() {
            //form select style: start
            if( $( 'form select option:selected' ).length === 0 ) {
                $( 'form select option:first[value=""]' ).attr( 'selected', 'selected' );
            }
            $( 'form select option:first[value=""]' ).attr( 'disabled', 'disabled' ).hide();
            //form select style: end

            $( '.cake-sql-log' ).addClass( 'table table-striped table-hover table-condensed table-responsive' );
        },
        /**
         * Show an abosolute positioned mask div over an area
         * 
         * @param {String} mask_container like #selector
         * @param {Integer} height
         */
        showMask: function( mask_container, height ) {
            if( !mask_container ) {
                mask_contaianer = 'body';
            }
            $( mask_container ).css( 'position', 'relative' );
            var mask_image = $( '<img src="' + mask_image_path + '" alt="Loading..." />' );
            var mask_element_height = height ? height : $( mask_container ).height();
            var mask_element = $( '<div class="ss_masking" />' ).css( {
                'position': 'absolute',
                'background-color': '#000',
                'opacity': 0.5,
                'filter': 'alpha(opacity = 50)',
                'z-index': 1000,
                'top': 0,
                'left': 0,
                'width': '100%',
                'height': mask_element_height,
                'text-align': 'center'
            } );
            $( mask_element ).append( mask_image );
            $( mask_container ).append( mask_element ).show();
            if( mask_element_height > mask_image.height() ) {
                mask_element.find( 'img' ).css( {'margin-top': mask_element_height / 2 - mask_image.height() / 2} );
            }
        },
        /**
         * Hide an abosolute positioned mask div over an area
         * 
         * @param {String} mask_container like #selector
         */
        hideMask: function( mask_container ) {
            if( !mask_container ) {
                mask_contaianer = 'body';
            }
            $( mask_container ).find( '.ss_masking' ).hide();
        },
        /**
         * Show a loading image in an area (caution: replaces HTML when showing loading image)
         * 
         * @param {String} image_container like #selector
         * @param {Integer} image_width
         */
        showImage: function( image_container, image_width ) {
            var image_element = $( '<div class="ss_loading" style="text-align: center;" />' );
            image_element.append( '<img width="' + image_width + '" src="' + loading_image_path + '" alt="Loading...">' );
            $( image_container ).html( image_element ).show();
        },
        /**
         * Hide a loading image in an area
         * 
         * @param {String} image_container like #selector
         */
        hideImage: function( image_container ) {
            $( image_container ).find( '.ss_loading' ).remove();
        },
        /**
         * Ajax call function
         * 
         * @param object settings
         * @property string setting.type post/get
         * @property string setting.url server path
         * @property string setting.dataType json/jsonp/html
         * @returns mixed
         */
        callAjax: function( settings ) {
            var config = {
                type: 'post',
                url: '',
                data: {},
                dataType: 'html',
                masking: true,
                masking_height: 150,
                loading_image_width: 100,
                ajax_container: 'body',
                error_display: 'dialog',
                cache: false,
                contentType: 'application/x-www-form-urlencoded;charset=UTF-8',
                success: function( data ) {
                    console.log( data );
                },
                error: function( data ) {
                    if( this.error_display === 'dialog' ) {
                        $( '<div>OOPS !! Server request failed.<br />' + data + '</div>' ).dialog( {
                            resizable: false,
                            height: 140,
                            modal: true,
                            title: 'Internal Server Error',
                            width: 400,
                            zIndex: 1000,
                            buttons: {
                                'Close': function() {
                                    $( this ).dialog( 'close' );
                                }
                            }
                        } );
                    }
                    else {
                        console.log( 'OOPS !! Server request failed.' + data );
                    }
                },
                beforeSend: function() {},
                complete: function() {}
            };
            if( settings ) {
                $.extend( config, settings );
            }
            $.ajax( {
                type: config.type,
                url: config.url,
                data: config.data,
                cache: config.cache,
                contentType: config.contentType,
                dataType: config.dataType,
                success: function( data ) {
                    config.success( data );
                },
                error: function( data ) {
                    config.error( data );
                },
                beforeSend: function() {
                    if( config.masking === true ) {
                        ss_utility.showMask( config.ajax_container );
                    }
                    else {
                        ss_utility.showImage( config.ajax_container, config.loading_image_width );
                    }
                    config.beforeSend();
                },
                complete: function() {
                    config.complete();
                    if( config.masking === true ) {
                        ss_utility.hideMask( config.ajax_container );
                    }
                    else {
                        ss_utility.hideImage( config.ajax_container );
                    }
                }
            } );
        },
        /**
         * 
         * @param {type} str
         * @returns {String}
         */
        captalizeEachWord: function( str ) {
            if( !str ) {
                return null;
            }
            var result = '';
            var words = str.split( '_' );
            for( var i in words ) {
                result += this.ucFirst( words[i] );
            }
            return result;
        },
        /**
         * 
         * @param {String} str
         * @returns {String}
         */
        stringifySeoFriendly: function( str ) {
            if( !str ) {
                return null;
            }
            str = $.trim( str.toLowerCase() ).replace( /\`|\!|\%|\&|\^|\'|\"|\~|\*|\[|\]|\?/g, '' ).replace( /\s+/g, '-' );
            return encodeURIComponent( str );
        },
        /**
         * 
         * @param {type} str
         * @returns {_L1.Anonym$0.removeSpecialCharacters.str|@exp;str@call;replace}
         */
        removeSpecialCharacters: function( str ) {
            return str ? str.replace( /[^a-zA-Z 0-9]+/g, '' ) : null;
        },
        /**
         * 
         * @param {type} length
         * @returns {String}
         */
        getRandomString: function( length ) {
            var s = '';
            var random_character = function() {
                var n = Math.floor( Math.random() * 62 );
                return n < 10 ? n : ( n < 36 ? String.fromCharCode( n + 55 ) : String.fromCharCode( n + 61 ) );
            };
            while( s.length < length ) {
                s += random_character();
            }
            return s;
        },
        /**
         * 
         * @param {type} str
         * @returns {@exp;str@call;toUpperCase|String|@exp;str@pro;substr@call;@call;toUpperCase|@exp;@exp;str@pro;substr@call;@call;toUpperCase|@exp;str@pro;substr@exp;@@call;exp;str@pro;substr@call;@call;toUpperCase}
         */
        ucFirst: function( str ) {
            if( str && str.length > 1 ) {
                return str.substr( 0, 1 ).toUpperCase() + str.substr( 1, str.length - 1 );
            }
            else if( str && str.length === 1 ) {
                return str.toUpperCase();
            }
            else {
                return '';
            }
        },
        /**
         * 
         * @returns {undefined}
         */
        clearSelection: function() {
            if( window.getSelection ) {
                if( window.getSelection().empty ) {  //Chrome
                    window.getSelection().empty();
                }
                else if( window.getSelection().removeAllRanges ) {  //Firefox
                    window.getSelection().removeAllRanges();
                }
            }
            else if( document.selection ) {  //IE
                document.selection.empty();
            }
        },
        /**
         * 
         * @param {type} name
         * @param {type} value
         * @param {type} expires
         * @param {type} path
         * @param {type} domain
         * @param {type} secure
         * @returns {undefined}
         */
        setCookie: function( name, value, expires, path, domain, secure ) {
            var today = new Date();
            today.setTime( today.getTime() );
            if( expires ) {
                expires = expires * 1000 * 60 * 60 * 24;
            }
            var expires_date = new Date( today.getTime() + expires );
            document.cookie = name + '::=' + value +
                    ( expires ? ';expires::=' + expires_date.toGMTString() : '' ) +
                    ( path ? ';path::=' + path : '' ) +
                    ( domain ? ';domain::=' + domain : '' ) +
                    ( secure ? ';secure::=' + secure : false );
        },
        /**
         * 
         * @param {type} name
         * @returns {String}
         */
        getCookie: function( name ) {
            var a_all_cookies = document.cookie.split( ';' );
            var a_temp_cookie = '';
            var cookie_name = '';
            var cookie_value = '';
            for( i = 0; i < a_all_cookies.length; i++ ) {
                a_temp_cookie = a_all_cookies[i].split( '::=' );
                cookie_name = a_temp_cookie[0].replace( /^\s+|\s+$/g, '' );
                if( cookie_name === name ) {
                    if( a_temp_cookie.length > 1 ) {
                        cookie_value = a_temp_cookie[1].replace( /^\s+|\s+$/g, '' );
                    }
                    return cookie_value;
                }
                a_temp_cookie = null;
                cookie_name = '';
            }
            return null;
        },
        /**
         * 
         * @param {type} name
         * @param {type} path
         * @param {type} domain
         * @returns {undefined}
         */
        deleteCookie: function( name, path, domain ) {
            if( this.getCookie( name ) ) {
                document.cookie = name + '::=' +
                        ( path ? ';path::=' + path : '' ) +
                        ( domain ? ';domain::=' + domain : '' ) +
                        ';expires=Thu, 01-Jan-1970 00:00:01 GMT';
            }
        },
        /**
         * Get value of radio/checkbox
         * 
         * @param elm object like $('#selector')
         * @return string
         */
        getCheckedValue: function( elm ) {
            var value = '';
            elm.each( function( i, v ) {
                if( $( this ).attr( 'checked' ) === true ) {
                    value = $( this ).val();
                }
            } );
            return value;
        },
        /**
         * 
         * @param {type} message
         * @param {type} title
         * @returns {undefined}
         */
        modalValidationError: function( message, title ) {
            message = message || 'There are some validation errors. Please check all inputs.';
            title = title || 'Validation Error';
            $( '<p>' + message + '</p>' ).dialog( {
                modal: true,
                title: title,
                buttons: {
                    'OK': function() {
                        $( this ).dialog( 'close' );
                    }
                }
            } );
        },
        /**
         * 
         * @param {type} email
         * @returns {@exp;filter@call;test}
         */
        validateEmail: function( email ) {
            var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return filter.test( email );
        },
        /**
         * 
         * @param {type} $form
         * @returns {@exp;$form@call;valid}
         */
        isValidForm: function( $form ) {
            return $form.valid();
        },
        /**
         * 
         * @param {type} form
         * @returns {@exp;@exp;form@pro;get@call;@call;reset|@exp;form@pro;get@call;@call;reset}
         */
        resetForm: function( form ) {
            return form.get( 0 ).reset();
        },
        /**
         * Submit form via ajax with validation
         * @param {type} $form
         * @param {type} settings
         * @returns {undefined}
         */
        formSubmitAjax: function( $form, settings ) {
            var config = {
                _btnValue: '',
                resetForm: false,
                data: undefined,
                dataType: 'json',
                btnLoadingText: 'Saving..',
                show_modalValidationError: false,
                beforeSend: function( $form ) {
                    ss_utility.showMask( $form );
                },
                buttonEnabled: function( $form ) {
                    $form.find( 'input[type="submit"]' ).html( this._btnValue );
                    $form.find( 'input[type="submit"]' ).removeAttr( 'disabled' );
                },
                beforeFormSubmit: undefined,
                afterSuccess: undefined
            };
            if( settings ) {
                $.extend( config, settings );
            }
            $form.submit( function() {
                if( ss_utility.isValidForm( $form ) ) {
                    $( this ).ajaxSubmit( {
                        resetForm: config.resetForm,
                        data: config.data,
                        dataType: config.dataType,
                        beforeSubmit: function() {
                            if( typeof config.beforeFormSubmit !== 'undefined' ) {
                                config.beforeFormSubmit( $form, config );
                            }
                            if( typeof config.beforeSend !== 'undefined' ) {
                                config.beforeSend( $form, config );
                            }
                        },
                        success: function( resp, statusText, xhr ) {
                            if( typeof config.afterSuccess !== 'undefined' ) {
                                config.afterSuccess( resp, $form );
                            }
                        },
                        complete: function( resp ) {
                            ss_utility.hideMask( $form );
                            if( typeof config.complete !== 'undefined' ) {
                                config.complete( resp.responseJSON );
                            }
                        }
                    } );
                }
                else {
                    config.show_modalValidationError ? ss_utility.modalValidationError() : '';
                }
                return false;
            } );
        },
        /**
         * Submit form via ajax without validation
         * @param {type} $form
         * @param {type} settings
         * @returns {undefined}
         */
        formSubmitAjaxNoValidation: function( $form, settings ) {
            var config = {
                _btnValue: '',
                resetForm: false,
                data: undefined,
                dataType: 'json',
                btnLoadingText: 'Saving..',
                show_modalValidationError: false,
                beforeSend: function( $form ) {
                    ss_utility.showMask( $form );
                },
                buttonEnabled: function( $form ) {
                    $form.find( 'input[type="submit"]' ).html( this._btnValue );
                    $form.find( 'input[type="submit"]' ).removeAttr( 'disabled' );
                },
                beforeFormSubmit: undefined,
                afterSuccess: undefined
            };
            if( settings ) {
                $.extend( config, settings );
            }
            
            $( this ).ajaxSubmit( {
                resetForm: config.resetForm,
                data: config.data,
                dataType: config.dataType,
                beforeSubmit: function() {
                    if( typeof config.beforeFormSubmit !== 'undefined' ) {
                        config.beforeFormSubmit( $form, config );
                    }
                    if( typeof config.beforeSend !== 'undefined' ) {
                        config.beforeSend( $form, config );
                    }
                },
                success: function( resp, statusText, xhr ) {
                    if( typeof config.afterSuccess !== 'undefined' ) {
                        config.afterSuccess( resp, $form );
                    }
                },
                complete: function( resp ) {
                    ss_utility.hideMask( $form );
                    if( typeof config.complete !== 'undefined' ) {
                        config.complete( resp.responseJSON );
                    }
                }
            } );
        },
        /**
         * Bind jQuery Select2
         * 
         * @param {object} settings
         * @property {String} settings.selector like #selector; default: .select2
         * @property {String} settings.placeholder any text; default: Select...
         */
        bindSelect2: function( settings ) {
            var config = {
                selector: '.select2',
                placeholder: 'Select...'
            };
            if( settings ) {
                $.extend( config, settings );
            }
            $( config.selector ).select2( {placeholder: config.placeholder} );
        },
        /**
         * Show message for a while specially for ajax form error
         * 
         * @param {String} msg_container selector like #selector
         * @param {String} msg
         * @param {String} type
         */
        showMessage: function( msg_container, msg, type, show_time ) {
            msg_container = !msg_container ? '#form_result' : msg_container;
            msg = !msg ? 'Something is wrong! Please check your input.' : msg;
            type = !type ? 'danger' : type;
            show_time = !show_time ? 5000 : show_time;
            msg = msg + '<button type="button" class="close" data-dismiss="alert" aria-hidden="true"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>';
            $( msg_container ).html( msg ).addClass( 'alert alert-' + type + ' fade in' ).show();
            setTimeout( function() {
                $( msg_container ).hide( 'slow', function() {
                    $( this ).html( '' ).removeClass( 'alert alert-' + type + ' fade in' );
                    //$.validator.reposition();
                } );
            }, show_time );
        },
        /**
         * Show error message for ajax form
         * 
         * @param {String} msg_container selector like #selector
         * @param {String} msg
         */
        showError: function( msg_container, msg ) {
            msg_container = !msg_container ? '#form_result' : msg_container;
            msg = !msg ? 'Something is wrong! Please check your input.' : msg;
            msg = '<button class="close" data-close="alert"></button>' + msg;
            $( msg_container ).html( msg ).show();
        },
        /**
         * Get bootstrap chcekbox
         * @param {type} selector
         */
        bootstrapCheckbox: function( checkbox ) {
            // Settings
            var $widget = checkbox,
                $button = $widget.find( 'button' ),
                $checkbox = $widget.find( 'input:checkbox' ),
                color = $button.data( 'color' ),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };
                
            // Event Handlers
            $button.on( 'click', function() {
                $checkbox.prop( 'checked', !$checkbox.is( ':checked' ) );
                $checkbox.triggerHandler( 'change' );
                updateDisplay();
            } );
            $checkbox.on( 'change', function() {
                updateDisplay();
            } );
            
            function updateDisplay() {
                var isChecked = $checkbox.is( ':checked' );

                // Set the button's state
                $button.data( 'state', ( isChecked ) ? 'on' : 'off' );

                // Set the button's icon
                $button.find( '.state-icon' ).removeClass().addClass( 'state-icon ' + settings[$button.data( 'state' )].icon );

                // Update the button's color
                if( isChecked ) {
                    $button.removeClass( 'btn-default' ).addClass( 'btn-' + color + ' active' );
                }
                else {
                    $button.removeClass( 'btn-' + color + ' active' ).addClass( 'btn-default' );
                }
            }
            
            function init() {
                updateDisplay();

                // Inject the icon if applicable
                if( $button.find( '.state-icon' ).length == 0 ) {
                    $button.prepend( '<i class="state-icon ' + settings[$button.data( 'state' )].icon + '"></i> ' );
                }
            }
            init();
        }
    }
}();

$( document ).ready( function() {
    ss_utility.init();

    /**
     * A helper function for floating labels above textbox or textarea in forms
     * @param {type} options
     * @returns {$.fn}
     */
    $.fn.floatLabels = function( options ) {
        // Settings
        var self = this;
        var settings = $.extend( {}, options );

        // Event Handlers
        function registerEventHandlers() {
            self.on( 'input keyup change', 'input, textarea', function() {
                actions.swapLabels( this );
            } );
        }

        // Actions
        var actions = {
            initialize: function() {
                self.each( function() {
                    var $this = $( this );
                    var $label = $this.children( 'label' );
                    var $field = $this.find( 'input,textarea' ).first();

                    if( $this.children().first().is( 'label' ) ) {
                        $this.children().first().remove();
                        $this.append( $label );
                    }

                    var placeholderText = ( $field.attr( 'placeholder' ) && $field.attr( 'placeholder' ) != $label.text() ) ? $field.attr( 'placeholder' ) : $label.text();

                    $label.data( 'placeholder-text', placeholderText );
                    $label.data( 'original-text', $label.text() );

                    if( $field.val() == '' ) {
                        $field.addClass( 'empty' )
                    }
                } );
            },
            swapLabels: function( field ) {
                var $field = $( field );
                var $label = $( field ).siblings( 'label' ).first();
                var isEmpty = Boolean( $field.val() );

                if( isEmpty ) {
                    $field.removeClass( 'empty' );
                    $label.text( $label.data( 'original-text' ) );
                }
                else {
                    $field.addClass( 'empty' );
                    $label.text( $label.data( 'placeholder-text' ) );
                }
            }
        }

        // Initialization
        function init() {
            registerEventHandlers();

            actions.initialize();
            self.each( function() {
                actions.swapLabels( $( this ).find( 'input,textarea' ).first() );
            } );
        }
        init();

        return this;
    };

    /**
     * A helper function that checks for the support of the 3D CSS3 transformations
     * @returns {Boolean}
     */
    $.support.css3d = function() {
        var props = ['perspectiveProperty', 'WebkitPerspective', 'MozPerspective'], testDom = document.createElement( 'a' );
        for( var i = 0; i < props.length; i++ ) {
            if( props[i] in testDom.style ) {
                return true;
            }
        }
        return false;
    };
} );