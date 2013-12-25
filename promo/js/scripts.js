/*global App:true */

jQuery( function ( $, undefined ) {
    "use strict";

    var $window = $( window ),
        $document = $( document ),
        $body = $( 'body' ),
        $wrapper = $( '#wrapper' );

    var wh = $window.height(),
        ww = $window.width();

    window.App = window.App || {};
    App = {
        defaults: App.defaults || {},
        data: App.data || {},
        helpers: App.helpers || {},
        Models: App.Models || {},
        Views: App.Views || {},
        Collections: App.Collections || {},

        init: function () {
            $window.scrollTop( 0 );
            $wrapper.css( 'display', 'block' );
            App.Views.Scroll.init();
            App.Views.Photo.init();
        }
    };


// DATA
// HELPERS
// MODELS
// VIEWS
    App.Views.Scroll = {
        els: {
            howto: {
                $el: $( '.b-howto' ),
                init: function () {

                    this.posY = this.$el.position().top;
                    this.height = this.$el.height();

                    this.$items = this.$el.find( '.item' );
                    this.$item1 = this.$el.find( '.item_1' );
                    this.$item2 = this.$el.find( '.item_2' );
                    this.$item3 = this.$el.find( '.item_3' );

                    this.item1Visible = false;
                    this.item2Visible = false;
                    this.item3Visible = false;

                    this.render();

                    $window.on( 'scrolled.pj', this.scroll );
                },

                render: function () {
//                    this.$items.css( {
//                        opacity: 0
//                    } );
                },

                scroll: function ( e, params ) {
                    var el = App.Views.Scroll.els.howto,
                        scrollTop = params.scrollTop;

                    // item 1
                    if ( scrollTop > el.posY - wh + el.height && !el.item1Visible ) {
                        fadeIn( el.$item1 );
                        el.item1Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height && el.item1Visible ) {
                        fadeOut( el.$item1 );
                        el.item1Visible = false;
                    }

                    // item 2
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 4 && !el.item2Visible ) {
                        fadeIn( el.$item2 );
                        el.item2Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 4 && el.item2Visible ) {
                        fadeOut( el.$item2 );
                        el.item2Visible = false;
                    }

                    // item 3
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 2 && !el.item3Visible ) {
                        fadeIn( el.$item3 );
                        el.item3Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 2 && el.item3Visible ) {
                        fadeOut( el.$item3 );
                        el.item3Visible = false;
                    }
                }
            },

            map: {
                $el: $( '.i-map' ),
                init: function () {

                    this.posY = this.$el.position().top;
                    this.height = this.$el.height();

                    this.$items = this.$el.find( '.b-map__pin' );
                    this.$item1 = this.$el.find( '.b-map__pin_1' );
                    this.$item2 = this.$el.find( '.b-map__pin_2' );
                    this.$item3 = this.$el.find( '.b-map__pin_3' );
                    this.$item4 = this.$el.find( '.b-map__pin_4' );
                    this.$item5 = this.$el.find( '.b-map__pin_5' );
                    this.$item6 = this.$el.find( '.b-map__pin_6' );

                    this.mapVisible = false;
                    this.item1Visible = false;
                    this.item2Visible = false;
                    this.item3Visible = false;
                    this.item4Visible = false;
                    this.item5Visible = false;
                    this.item6Visible = false;

                    this.render();

                    $window.on( 'scrolled.pj', this.scroll );
                },

                render: function () {
//                    this.$el.add(this.$items).css( {
//                        opacity: 0
//                    } );
                },

                scroll: function ( e, params ) {
                    var el = App.Views.Scroll.els.map,
                        scrollTop = params.scrollTop;

                    // el
                    if ( scrollTop > el.posY - wh + el.height && !el.elVisible ) {
                        fadeIn( el.$el );
                        el.elVisible = true;

                    } else if ( scrollTop < el.posY - wh + el.height && el.elVisible ) {
                        fadeOut( el.$el );
                        el.elVisible = false;
                    }

                    // item 1
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 6 && !el.item1Visible ) {
                        fadeIn( el.$item1 );
                        el.item1Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 6 && el.item1Visible ) {
                        fadeOut( el.$item1 );
                        el.item1Visible = false;
                    }

                    // item 2
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 3 && !el.item2Visible ) {
                        fadeIn( el.$item2 );
                        el.item2Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 3 && el.item2Visible ) {
                        fadeOut( el.$item2 );
                        el.item2Visible = false;
                    }

                    // item 3
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 2 && !el.item3Visible ) {
                        fadeIn( el.$item3 );
                        el.item3Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 2 && el.item3Visible ) {
                        fadeOut( el.$item3 );
                        el.item3Visible = false;
                    }

                    // item 4
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 1.5 && !el.item4Visible ) {
                        fadeIn( el.$item4 );
                        el.item4Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 1.5 && el.item4Visible ) {
                        fadeOut( el.$item4 );
                        el.item4Visible = false;
                    }

                    // item 5
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 1.2 && !el.item5Visible ) {
                        fadeIn( el.$item5 );
                        el.item5Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 1.2 && el.item5Visible ) {
                        fadeOut( el.$item5 );
                        el.item5Visible = false;
                    }

                    // item 6
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) && !el.item6Visible ) {
                        fadeIn( el.$item6 );
                        el.item6Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) && el.item6Visible ) {
                        fadeOut( el.$item6 );
                        el.item6Visible = false;
                    }
                }
            },

            scheme: {
                $el: $( '.b-scheme__algorithm' ),
                init: function () {

                    this.posY = this.$el.offset().top;
                    this.height = this.$el.height();

                    this.$items = this.$el.find( '.item' );
                    this.$item1 = this.$el.find( '.item_problem' );
                    this.$item2 = this.$el.find( '.item_next' ).eq( 0 );
                    this.$item3 = this.$el.find( '.item_say' );
                    this.$item4 = this.$el.find( '.item_next' ).eq( 1 );
                    this.$item5 = this.$el.find( '.item_resolve' );

                    this.item1Visible = false;
                    this.item2Visible = false;
                    this.item3Visible = false;
                    this.item4Visible = false;
                    this.item5Visible = false;

                    this.render();

                    $window.on( 'scrolled.pj', this.scroll );
                },

                render: function () {
//                    this.$items.css( {
//                        opacity: 0
//                    } );
                },

                scroll: function ( e, params ) {
                    var el = App.Views.Scroll.els.scheme,
                        scrollTop = params.scrollTop;

                    // item 1
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 5 && !el.item1Visible ) {
                        fadeIn( el.$item1 );
                        el.item1Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 5 && el.item1Visible ) {
                        fadeOut( el.$item1 );
                        el.item1Visible = false;
                    }

                    // item 2
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 2.5 && !el.item2Visible ) {
                        fadeIn( el.$item2 );
                        el.item2Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 2.5 && el.item2Visible ) {
                        fadeOut( el.$item2 );
                        el.item2Visible = false;
                    }

                    // item 3
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 1.7 && !el.item3Visible ) {
                        fadeIn( el.$item3 );
                        el.item3Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 1.7 && el.item3Visible ) {
                        fadeOut( el.$item3 );
                        el.item3Visible = false;
                    }

                    // item 4
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) / 1.25 && !el.item4Visible ) {
                        fadeIn( el.$item4 );
                        el.item4Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) / 1.25 && el.item4Visible ) {
                        fadeOut( el.$item4 );
                        el.item4Visible = false;
                    }

                    // item 5
                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) && !el.item5Visible ) {
                        fadeIn( el.$item5 );
                        el.item5Visible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) && el.item5Visible ) {
                        fadeOut( el.$item5 );
                        el.item5Visible = false;
                    }
                }
            },

            work: {
                $el: $( '.b-work' ),
                init: function () {

                    this.posY = this.$el.offset().top;
                    this.height = this.$el.height();

                    this.$comments = this.$el.find( '.b-work__comments' );
                    this.commentsPosY = this.$comments.offset().top;
                    this.$items = this.$el.find( '.item' );
                    this.itemsWidth = this.$items.outerWidth( true );

                    this.$scrollbar = $( '.b-work__scrollbar' );
                    this.$scrollbarHandler = this.$scrollbar.find( '.handler' );
                    this.scrollbarWidth = this.$scrollbar.width();
                    this.scrollbarHandlerWidth = this.$scrollbarHandler.width();

                    this.addHeight = this.$items.length * 500;

                    this.wrapperFixed = false;
                    this.wrapperFixedPos = 0;
                    this.commentsSlideFinished = false;

                    this.render();

                    $window.on( 'scrolled.pj', this.scroll );
                },

                render: function () {
                    var width = 0;

                    $body.height( $body.height() + this.addHeight );

                    $.each( this.$items, function () {
                        width += $( this ).outerWidth();
                    } );

                    this.commentsWidth = width;
                    this.$comments.width( width );
                },

                resetWrapper: function ( offset ) {
                    var el = App.Views.Scroll.els.work;

                    offset = offset || 0;

                    el.wrapperFixed = false;

                    if ( offset > 0 ) {
                        $wrapper.css( {
                            position: 'relative',
                            top: el.addHeight
                        } );
                        $( document ).scrollTop( offset + el.addHeight );

                    } else {
                        $wrapper.css( {
                            position: 'relative',
                            top: 0
                        } );
                    }

                },

                scroll: function ( e, params ) {
                    var el = App.Views.Scroll.els.work,
                        scrollTop = params.scrollTop,
                        offsetRatio = 0,
                        elTop = el.posY - (wh - el.height) / 2;


                    if ( scrollTop >= elTop ) {

                        // scroll comments tape & scrollbar
                        if ( !el.commentsSlideFinished ) {
                            if ( !el.wrapperFixed ) {
                                el.wrapperFixed = true;
                                el.wrapperFixedPos = scrollTop;
                                $wrapper.css( {
                                    position: 'fixed',
                                    top: -scrollTop
                                } );
                            }

                            if ( el.wrapperFixed ) {

                                offsetRatio = (scrollTop - el.wrapperFixedPos) / el.addHeight;

                                // scrolling
                                if ( offsetRatio < 1 ) {
                                    el.$comments.css( {
                                        left: -offsetRatio * (el.commentsWidth - el.itemsWidth)
                                    } );

                                    el.$scrollbarHandler.css( {
                                        left: offsetRatio * (el.scrollbarWidth - el.scrollbarHandlerWidth)
                                    } );

                                    // scroll finished
                                } else {
                                    el.commentsSlideFinished = true;
                                    el.resetWrapper( el.wrapperFixedPos );

                                    el.$comments.css( {
                                        left: -(el.commentsWidth - el.itemsWidth)
                                    } );

                                    el.$scrollbarHandler.css( {
                                        left: el.scrollbarWidth - el.scrollbarHandlerWidth
                                    } );
                                }
                            }

                        } else if ( scrollTop <= elTop + el.addHeight ) {
                            if ( !el.wrapperFixed ) {
                                el.wrapperFixed = true;
                                el.wrapperFixedPos = scrollTop - el.addHeight;
                                $wrapper.css( {
                                    position: 'fixed',
                                    top: -el.wrapperFixedPos
                                } );
                            }

                            el.commentsSlideFinished = false;
                        }


                    } else {

                        if ( el.wrapperFixed ) {
                            el.resetWrapper();
                        }

                    }
                }
            },

            mobile: {
                $el: $( '.b-mobile' ),
                init: function () {

                    this.posY = this.$el.position().top;
                    this.height = this.$el.height();

                    this.$item1 = this.$el.find( '.mobile1' );
                    this.$item2 = this.$el.find( '.mobile2' );

                    this.itemsVisible = false;

                    this.render();

                    $window.on( 'scrolled.pj', this.scroll );
                },

                render: function () {
                    this.$item1.css( {
                        left: -ww
                    } );
                    this.$item2.css( {
                        right: -ww
                    } );
                },

                scroll: function ( e, params ) {
                    var el = App.Views.Scroll.els.mobile,
                        scrollTop = params.scrollTop;

                    if ( scrollTop > el.posY - 200 && !el.itemsVisible ) {
                        el.$item1.animate( {
                            left: 100
                        }, 300 );
                        el.$item2.animate( {
                            right: 220
                        }, 300 );
                        el.itemsVisible = true;

                    } else if ( scrollTop < el.posY - 200 && el.itemsVisible ) {
                        el.$item1.animate( {
                            left: -ww
                        } );
                        el.$item2.animate( {
                            right: -ww
                        } );
                        el.itemsVisible = false;
                    }
                }
            },

            together: {
                $el: $( '.b-together' ),
                init: function () {

                    this.posY = this.$el.position().top;
                    this.height = this.$el.height();

                    this.itemsVisible = false;

                    $window.on( 'scrolled.pj', this.scroll );
                },

                scroll: function ( e, params ) {
                    var el = App.Views.Scroll.els.together,
                        scrollTop = params.scrollTop;

                    if ( scrollTop > el.posY - wh + el.height + (el.posY - scrollTop) && !el.itemsVisible ) {
                        el.$el.fadeTo( 300, 1 );
                        el.itemsVisible = true;

                    } else if ( scrollTop < el.posY - wh + el.height + (el.posY - scrollTop) && el.itemsVisible ) {
                        el.$el.fadeTo( 300, 0 );
                        el.itemsVisible = false;
                    }
                }
            }
        },

        init: function () {

            this.els.howto.init();
            this.els.map.init();
            this.els.scheme.init();
            this.els.work.init();
            this.els.mobile.init();
            this.els.together.init();

            $window.on( 'scroll', this.scroll );
        },

        scroll: function () {
            var scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            $body.trigger( 'scrolled.pj', {scrollTop: scrollTop} );
        }
    };

    App.Views.Photo = {
        $el: $( '.b-whoWeAre' ),
        $photo: $( '.b-whoWeAre__photo' ),
        photoWidth: 0,

        init: function () {
            this.render();
            this.drag();
        },

        render: function () {
            var $photo = this.$photo,
                $images = $photo.find( 'img' );

            var width = 0;

            $.each( $images, function () {
                width += $( this ).outerWidth( true );
                width += 5;
            } );

            this.photoWidth = width;
            $photo.width( width );
            this.boundingBoxWidth = this.$el.find( '.boundingBox' ).width();
        },

        drag: function () {
            var $photo = this.$photo,
                photoWidth = this.photoWidth,
                boundingBoxWidth = this.boundingBoxWidth,
                $thumbs = this.$el.find( '.b-whoWeAre__thumbs' ),
                $frame = $thumbs.find( '.frame' ),
                offset = 0;

            var thumbsWidth = $thumbs.width(),
                frameWidth = $frame.outerWidth( true );

            $frame.draggable( {
                axis: 'x',
                drag: function ( e, ui ) {
                    var pos = ui.position,
                        ratio;

                    if ( pos.left < 0 ) {
                        pos.left = 0;
                    }

                    if ( pos.left + frameWidth > thumbsWidth ) {
                        pos.left = thumbsWidth - frameWidth;
                    }

                    ratio = pos.left / (thumbsWidth - frameWidth);
                    ratio = ratio > 1 ? 1 : ratio;
                    offset = (boundingBoxWidth - photoWidth) * ratio;

                    $photo.css( {
                        left: offset
                    } );
                }
            } );
        }
    };

// COLLECTIONS
// INIT
    App.init();


    $window.resize( function () {
        wh = $window.height();
    } );
    $document.on( {
        keydown: function ( e ) {
            var code = (e.keyCode ? e.keyCode : e.which);
            if ( code === 35 || code === 36 ) {
                e.preventDefault();
            }
        }
    } );

    function fadeIn( $obj ) {
        if ( window.isMsIe && window.isMsIe <= 8 ) {
            fadeIn = function ( $obj ) {
                $obj.css( 'visibility', 'visible' );
            }

        } else {
            fadeIn = function ( $obj ) {
                $obj.fadeTo( 300, 1 );
            }
        }

        fadeIn( $obj );
    }

    function fadeOut( $obj ) {
        if ( window.isMsIe && window.isMsIe <= 8 ) {
            fadeOut = function ( $obj ) {
                $obj.css( 'visibility', 'hidden' );
            }

        } else {
            fadeOut = function ( $obj ) {
                $obj.fadeTo( 300, 0 );
            }
        }

        fadeOut( $obj );
    }
} );
