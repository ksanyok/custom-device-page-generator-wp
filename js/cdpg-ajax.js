document.addEventListener('DOMContentLoaded', function() {
    var expandElems = document.querySelectorAll('.expand-rows');
    console.log('expandElems:', expandElems); // проверяем, что кнопки нашлись
    expandElems.forEach(function(expandElem) {
        expandElem.addEventListener('click', function() {
            console.log('Clicked!'); // проверяем, что событие срабатывает
            var parentRow = expandElem.closest('tr');
            var postId = parentRow.querySelector('.row-count').dataset.postId;
            console.log('postId:', postId); // проверяем, что postId выбирается правильно
            var subRows = document.querySelectorAll('.sub-row[data-post-id="' + postId + '"]');
            console.log('subRows:', subRows); // проверяем, что подстроки нашлись
            subRows.forEach(function(subRow) {
                subRow.style.display = subRow.style.display === 'none' ? '' : 'none';
            });
        });
    });
});

document.getElementById('cdpg-modal-close').addEventListener('click', function() {
    document.getElementById('cdpg-modal').style.display = 'none';
});



(function($) {
    // Глобальная переменная для отслеживания, была ли нажата кнопка "Сгенерировать все услуги"
    var isGeneratingAll = false;

    function updateGenerateAllButtonStatus() {
        $("button[id^='generate-all-']").each(function() {
            var button = $(this);
            var postId = button.attr("id").split("-")[2];
            var allServicesGenerated = true;

            $(".generate-single-service-button[data-id='" + postId + "']").each(function() {
                var serviceButton = $(this);
                var serviceRow = serviceButton.closest("tr");
                var serviceCell = serviceRow.find("td:eq(1)");
                
                if (!serviceButton.is(":disabled") && serviceCell.text().includes("Замена")) {
                    allServicesGenerated = false;
                }
            });

            if (allServicesGenerated) {
                button.attr("disabled", "disabled");
            } else {
                button.removeAttr("disabled");
            }
        });
    }

    $(document).ready(function() {
        updateGenerateAllButtonStatus();
        
        $('.generate-single-service-button').on('click.promise', function(e) {
            e.preventDefault();
            var postId = $(this).data('id');
            var rowId = $(this).data('row-id');
            var button = $(this);

            button.css('opacity', '0.5');

            return $.ajax({
                url: cdpg_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'cdpg_generate_single_service',
                    nonce: cdpg_ajax.nonce,
                    post_id: postId,
                    row_id: rowId
                }
				
				
				
        }).done(function(response) {
            button.css('opacity', '1');
            button.attr('disabled', 'disabled');

            if (response) {
                var trimmedResponse = response.trim();
                var successHTML = $(trimmedResponse);

                if (isGeneratingAll) {
                    $('#cdpg-modal-content ul').append('<li>' + successHTML.html() + '</li>');
                } else {
                    $('#cdpg-modal-content').html(successHTML);
                    $('#cdpg-modal').css('display', 'block');
                }
            }
        }).fail(function(xhr, textStatus, errorThrown) {

				
				
                button.css('opacity', '1');
                var errorHTML = '<div class="notice notice-error"><p>Произошла ошибка! Пожалуйста, попробуйте еще раз.</p></div>';
                $('#cdpg-modal-content').html(errorHTML);
                $('#cdpg-modal').css('display', 'block');

                updateGenerateAllButtonStatus();
            });
        });

        $("button[id^='generate-all-']").on("click", function() {
            var button = $(this);
            var postId = button.attr("id").split("-")[2];
            var promises = [];
            isGeneratingAll = true;

            // Начинаем со свежего списка уведомлений
            $('#cdpg-modal-content').html('<ul></ul>');
            $('#cdpg-modal').css('display', 'block');

            $(".generate-single-service-button[data-id='" + postId + "']").each(function() {
                var serviceButton = $(this);

                if (!serviceButton.is(":disabled")) {
                    var serviceRow = serviceButton.closest("tr");
                    var serviceCell = serviceRow.find("td:eq(1)");
                    
                    if (serviceCell.text().includes("Замена")) {
                        promises.push(serviceButton.triggerHandler('click.promise'));
                    }
                }
            });

            $.when.apply($, promises).done(function() {
                console.log('All services have been generated');
                isGeneratingAll = false;
            }).fail(function() {
                isGeneratingAll = false;
            });
        });
    });
}(jQuery));

           





/*
(function($) {
    function updateGenerateAllButtonStatus() {
        $("button[id^='generate-all-']").each(function() {
            var button = $(this);
            var postId = button.attr("id").split("-")[2];

            var allButtonsDisabled = true;
            $(".generate-single-service-button[data-id='" + postId + "']").each(function() {
                var serviceButton = $(this);

                var serviceRow = serviceButton.closest("tr");
                var serviceCell = serviceRow.find("td:eq(1)");
                
                if (serviceCell.text().includes("Замена") && !serviceButton.is(":disabled")) {
                    allButtonsDisabled = false;
                    return false; // прерываем цикл .each, поскольку нашли незаблокированную кнопку
                }
            });

            if (allButtonsDisabled) {
                button.attr('disabled', 'disabled');
            } else {
                button.removeAttr('disabled');
            }
        });
    }

    $(document).ready(updateGenerateAllButtonStatus);

    $('.generate-single-service-button').on('click.promise', function(e) {
        e.preventDefault();

        var postId = $(this).data('id');
        var subRows = $(this).closest('tr').nextUntil('.row');
        var rowId = $(this).data('row-id');
        var button = $(this);

        button.css('opacity', '0.5');

        return $.ajax({
            url: cdpg_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'cdpg_generate_single_service',
                nonce: cdpg_ajax.nonce,
                post_id: postId,
                row_id: rowId
            }
        }).done(function(response) {
            button.css('opacity', '1');
            
            if (response) {
                var trimmedResponse = response.trim();

                var successHTML = $(trimmedResponse);
                $('#cdpg-modal-content').html(successHTML);
                $('#cdpg-modal').css('display', 'block');

                button.attr('disabled', 'disabled');
            }

            updateGenerateAllButtonStatus();
        }).fail(function(xhr, textStatus, errorThrown) {
            button.css('opacity', '1');

            var errorHTML = '<div class="notice notice-error"><p>Произошла ошибка! Пожалуйста, попробуйте еще раз.</p></div>';
            $('#cdpg-modal-content').html(errorHTML); // добавляем HTML в модальное окно
            $('#cdpg-modal').css('display', 'block'); // показываем модальное окно
            
            updateGenerateAllButtonStatus();
        });
    });

    $("button[id^='generate-all-']").on("click", function() {
        var button = $(this);
        var postId = button.attr("id").split("-")[2];

        var promises = [];

        $(".generate-single-service-button[data-id='" + postId + "']").each(function() {
            var serviceButton = $(this);
            
            if (!serviceButton.is(":disabled")) {
                var serviceRow = serviceButton.closest("tr");
                var serviceCell = serviceRow.find("td:eq(1)");
                
                if (serviceCell.text().includes("Замена")) {
                    promises.push(serviceButton.triggerHandler('click.promise'));
                }
            }
        });

        $.when.apply($, promises).done(function() {
            console.log('All services have been generated');
            updateGenerateAllButtonStatus();
        });
    });
})(jQuery);

*/