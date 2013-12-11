(function ($) {
    'use strict';
    $.fn.customTreeView = function (options) {
        // The 'options' parameter must be an object
        if (typeof options !== 'object' || options === null || Array.isArray(options)) {
            options = {};
        }


        // Replace Normal space with Non-breaking space
        $(this).find('li').each(function(){
            var text = $(this).children().first().html();
            $(this).children().first().html(text.replace(/ /g, "&nbsp;"));
        });

        // Collapse Nested Items
        $(this)
            .find('li > ul')
                .toggleClass('Collapsed');


        // Put the Bullet element
        $(this)
            .find('li > ul').each(function(){
                $(this)
                    .siblings()
                        .first()
                            .before("<img class='TreeView List-Bullet' src='Images/listArrowBlue.png' alt='Collapsed' />&nbsp;");
            });

        $(this)
            .find('li:not(:has(> ul))').each(function(){
                $(this)
                    .addClass('LeafItem')
                        .css({'padding-left': '21px'});
            });

        /*
        $(this)
            .find('img.list-bullet')
                .parent()
                    .not(':has(> ul)')
                        .each(function(){
                            $(this)
                                .children()
                                    .first()
                                        .remove();
                        });
        */

        // Click events for all Bullets:
        $(this)
            .find('li > img.TreeView.List-Bullet')
                .click(function(){
                    $(this).toggleClass('Collapsed').parent().find('> ul').toggleClass('Collapsed');
                });

        // Bind the links with the iframe
        $(this).find('a').attr('target', 'PlanetDetails');

        return this;
    };
}(jQuery));