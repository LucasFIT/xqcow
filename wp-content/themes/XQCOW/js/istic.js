/**
 * @author xqcow - XQCOW
 *
 * @description This file contains all the javascript functions for the theme
 *
 * @package xqcow
 */

jQuery(document).ready(function($) {
    /**
     * Responsive menu
     */
    function reorderMenuItems() {
        if ($(window).width() < 1140) {
            $(".xqcow-header-part").appendTo($("#xqcow-mobile-menu"));
            $(".xqcow-secondary-container").appendTo($("#xqcow-mobile-menu"));
        } else {
            $(".xqcow-header-part").appendTo($(".xqcow-container-header"));
            $(".xqcow-secondary-container").appendTo($(".site-header"));
        }
    }
    reorderMenuItems();

    /**
     * Sub menu mobile toggle
     */
    var subMenus = $(".menu-item > .sub-menu");

    subMenus.each(function() {
        if ($(window).width() > 1140) {
            return false;
        }

        $(this)
            .hide()
            .parent()
            .append("<i class='fa fa-plus'></i>")
            .on("click", function() {
                $(this)
                    .find("i")
                    .toggleClass("fa-plus fa-minus");
                $(this)
                    .find(".sub-menu")
                    .toggle("fast");
            });
    });

    $(window).resize(function() {
        reorderMenuItems();
    });

    /**
     * Stick scroll menu
     */
    window.onscroll = function() {
        stickMenu();
    };

    var secondaryMenu = document.getElementById("secondary-menu");
    var stick = secondaryMenu.offsetTop;

    function stickMenu() {
        if ($(window).width() > 1140 && window.pageYOffset > stick) {
            secondaryMenu.classList.add("sticky");
        } else {
            secondaryMenu.classList.remove("sticky");
        }
    }

    /**
     * Toggle menu
     */
    function openMenu() {
        $("#xqcow-mobile-menu").css({ width: "300px" });
        $(".xqcow-label").show("fast");
        $(".xqcow-cart-count").show("fast");
    }

    function closeMenu() {
        $("#xqcow-mobile-menu").css({ width: "0" });
        $(".xqcow-label").hide("fast");
        $(".xqcow-cart-count").hide("fast");
    }

    $("#xqcow-menu-check").on("click", openMenu);
    $("#xqcow-closebtn").on("click", closeMenu);

    /**
     * Validate product comment box
     */

    // Disables the button that sends the evaluation if the customer has not yet typed anything
    $(".woocommerce #respond input#submit").prop("disabled", true);

    // Add a placeholder on comment box
    $(".woocommerce #reviews #comment").attr(
        "placeholder",
        "O que achou desse produto?"
    );

    // Enable or disable the comment box
    $(".woocommerce #reviews #comment").bind("input keyup paste", function() {
        var ratingSize = $(this).val().length;

        if (ratingSize > 3) {
            $(".woocommerce #respond input#submit").prop("disabled", false);
        } else {
            $(".woocommerce #respond input#submit").prop("disabled", true);
        }
    });

    /**
     * Translate WooCommerce Order Select
     */
    (woof_lang.orderby = "Ordenar por"),
    (woof_lang.date = "data"),
    (woof_lang.perpage = "por página"),
    (woof_lang.pricerange = "preço"),
    (woof_lang.menu_order = "ordem do menu"),
    (woof_lang.popularity = "popularidade"),
    (woof_lang.rating = "avaliação"),
    (woof_lang.price = "preço do menor pro maior"),
    (woof_lang["price-desc"] = "preço do maior pro menor");

    /**
     * Scroll to top button
     */
    function scrollToTop() {
        var btn = $("#scroll-to-top");

        $(window).scroll(function() {
            if ($(window).scrollTop() > 300) {
                btn.addClass("show");
            } else {
                btn.removeClass("show");
            }
        });

        btn.on("click", function(e) {
            e.preventDefault();
            $("html, body").animate({ scrollTop: 0 }, "300");
        });
    }

    scrollToTop();

    // overwrite woocommerce scroll to notices
    $.scroll_to_notices = function(scrollElement) {
        var offset = 465;
        if (scrollElement.length) {
            $("html, body").animate({
                    scrollTop: scrollElement.offset().top - offset,
                },
                1000
            );
        }
    };

    // Enable tooltips
    $(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
});