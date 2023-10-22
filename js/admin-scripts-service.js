// Функция для отображения дополнительного поля
function showOtherField(selectedValue, otherFieldId) {
    var otherField = jQuery('#' + otherFieldId);
    if (selectedValue == 'other') {
        otherField.show();
    } else {
        otherField.hide();
    }
}

// функция для обновления превью заголовка
function updatePreviewTitle(serviceId, otherFieldId, deviceId, additionalFieldId, previewId) {
    var service = jQuery('#' + serviceId).val();
    if (service == 'other') {
        service = jQuery('#' + otherFieldId).val();
    }

    var device = jQuery('#' + deviceId).val();
    var additional = jQuery('#' + additionalFieldId).val();

    var title = service + ' ' + device + ' ' + additional;

    jQuery('#' + previewId).text(title);
}

jQuery(document).ready(function ($) {
    console.log("Checking for elements...");
    console.log(".cdpg-tab: ", $('.cdpg-tab').length);
    console.log(".cdpg-inner-tab: ", $('.cdpg-inner-tab').length);

    // Для верхних вкладок
    $('.cdpg-tab').click(function () {
        console.log("cdpg-tab clicked");
        var tabId = $(this).attr('id') + '-content';

        $('.cdpg-tab').removeClass('cdpg-tab-active');
        $(this).addClass('cdpg-tab-active');

        $('.cdpg-tab-content-item').removeClass('cdpg-tab-content-active');
        $('#' + tabId).addClass('cdpg-tab-content-active');
    });

    // Для внутренних вкладок
    $('.cdpg-inner-tab').click(function () {
        console.log("cdpg-inner-tab clicked");
        var tabId = $(this).attr('id') + '-content';

        $('.cdpg-inner-tab').removeClass('cdpg-inner-tab-active');
        $(this).addClass('cdpg-inner-tab-active');

        $('.cdpg-inner-tab-content-item').removeClass('cdpg-inner-tab-content-active');
        $('#' + tabId).addClass('cdpg-inner-tab-content-active');
    });

    // Применение функции к выпадающим спискам
    $('#service_ru').change(function () {
        showOtherField($(this).val(), 'other_field_ru');
    });

    $('#service_ee').change(function () {
        showOtherField($(this).val(), 'other_field_ee');
    });

    // вызов функции обновления превью заголовка при загрузке страницы и при любом изменении полей
    updatePreviewTitle('service_ru', 'other_field_ru', 'device_model_ru', 'additional_field_ru', 'preview_title_ru');
    updatePreviewTitle('service_ee', 'other_field_ee', 'device_model_ee', 'additional_field_ee', 'preview_title_ee');

    $('#service_ru, #other_field_ru, #device_model_ru, #additional_field_ru').change(function () {
        updatePreviewTitle('service_ru', 'other_field_ru', 'device_model_ru', 'additional_field_ru', 'preview_title_ru');
    });

$('#service_ee, #other_field_ee, #device_model_ee, #additional_field_ee').change(function () {
        updatePreviewTitle('service_ee', 'other_field_ee', 'device_model_ee', 'additional_field_ee', 'preview_title_ee');
    });
	
	
	
    var placeholderModel = "Huawei P30 Pro"; // модель-заполнитель

    // функция для замены модели-заполнителя на новую модель
    function replaceModel(oldModel, newModel) {
        // проходим по всем полям ввода и выпадающим спискам
        $('input, select').each(function () {
            var $this = $(this);
            // если изначальное значение не сохранено, сохраняем его
            if (!$this.data('initialValue')) {
                $this.data('initialValue', $this.val());
            }
            // заменяем значение в поле ввода или выпадающем списке
            var initialValue = $this.data('initialValue');
            var newValue = initialValue.replace(oldModel, newModel);
            if ($this.is('input')) {
                $this.val(newValue);
            } else if ($this.is('select')) {
                $this.find('option').each(function () {
                    var $option = $(this);
                    // если изначальное значение не сохранено, сохраняем его
                    if (!$option.data('initialValue')) {
                        $option.data('initialValue', $option.val());
                    }
                    // заменяем значение
                    var optionInitialValue = $option.data('initialValue');
                    var optionNewValue = optionInitialValue.replace(oldModel, newModel);
                    $option.val(optionNewValue);
                    $option.text(optionNewValue);
                });
            }
            // обновляем сохраненное значение
            $this.data('initialValue', newValue);
        });
    }

    // отслеживаем изменение поля с моделью устройства
    $('#device_model_ru, #device_model_ee').on('blur', function () {
        var newModel = $(this).val();
        replaceModel(placeholderModel, newModel);
        placeholderModel = newModel; // обновляем модель-заполнитель
    });
	
	
});
