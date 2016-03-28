(function ($) {
    'use strict';

    if (window.TABLE_HIGHLIGHT === undefined) {
        return;
    }

    var setColor = function (nodes, color) {
            var $nodes = nodes;

            if (~color.indexOf('.')) {
                $nodes.addClass(color.replace('.', ' '));
            } else {
                $nodes.css('background-color', color);
            }
        },

        unsetColor = function (nodes, color) {
            var $nodes = nodes;

            if (~color.indexOf('.')) {
                $nodes.removeClass(color.replace('.', ' '));
            } else {
                $nodes.css('background-color', '');
            }
        },

        highlight = function ($table, data) {
            var $dataTable = $table.DataTable(),
                $frame = $table.closest('.dataTables_wrapper');

            if (data.rows === null && data.cols === null) {
                return;
            }

            $frame.find('td, th').on('mouseenter', function () {
                var colClass = this.className.match(/column-\d+/);

                if (data.cols && colClass) {
                    setColor($frame.find('.' + colClass), data.cols);
                }

                if (data.rows) {
                    setColor($(this).closest('tr'), data.rows);
                }
            });

            $frame.find('td, th').on('mouseleave', function () {
                var colClass = this.className.match(/column-\d+/);

                if (data.cols && colClass) {
                    unsetColor($frame.find('.' + colClass), data.cols);
                }

                if (data.rows) {
                    unsetColor($(this).closest('tr'), data.rows);
                }
            });
    };

    $.each(window.TABLE_HIGHLIGHT, function (tableId, data) {
        var $table = $('#' + tableId);

        $table.on( 'draw.dt', function () {
            highlight($table, data);
        });
    });
})(jQuery);
