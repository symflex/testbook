(function ($) {
    let items = [];

    $.validator.addMethod(
        'regexp',
        function(value, element, pattern) {
            const regexp = new RegExp(pattern);
            return this.optional(element) || regexp.test(value)
        },
        'Недоспустимые символы'
    );

    $(document).on('change', '[name="image"]', function (e) {
        $(this).rules('add', {
            required: true,
            accept: "image/jpeg, image/png"
        });

        const image = this.files[0];

        if (image.type.match(/image.*/)) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('.preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(image);
        }
    });

    let validator = $('#form-item').validate({
        lang: 'ru',
        rules: {
            'Item[name]': {
                required: true,
                minlength: 2,
                regexp: '^[A-Za-zА-Яа-я]+$'
            },
            'Item[surname]': {
                required: true,
                minlength: 2,
                regexp: '^[A-Za-zА-Яа-я]+$'
            },
            'Item[phone]': {
                required: true,
                number: true
            },
            'Item[email]': {
                required: true,
                regexp: '^\\S+@\\S+\\.\\S+$'
            }
        },
        messages: {
            'Item[email]': {
                regexp: 'Пожалуйста, введите корректный адрес электронной почты.'
            }
        },
        submitHandler: function (form) {
            const action = $(form).attr('action');
            const formData = new FormData(form);
            const modal = $('#itemForm');

            $.ajax({
                url: action,
                method: 'post',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json'
            }).done(function (result) {

                if (result.errors) {
                    validator.showErrors(result.errors);
                } else {
                    if (action.match(/update*/)) {
                        items = items.map(item => item.id == result.id ? result : item);
                    } else {
                        items.push(result)
                    }

                    $(modal).modal('toggle')
                    renderList(items)
                }
            });
            return false;
        }
    });


    $(document).on('click', '#save-item', function (e) {
        let form = ('#form-item');
        $(form).trigger('submit')
    });

    function templateRow(strings, item, num) {
        const template =
                `<tr data-id="${item.id}">
                     <td align="center">${num}</td>
                     <td>${item.name}</td>
                     <td>${item.surname}</td>
                     <td>${item.phone}</td>
                     <td>${item.email}</td>
                     <td width="100" align="center">
                        <img src="/images/${item.image}" class="image">
                     </td>
                     <td width="100" align="center">
                         <div class="btn btn-danger btn-sm" data-delete="${item.id}"><i class="fa fa-trash"></i></div>
                         <div class="btn btn-success btn-sm" data-edit="${item.id}"><i class="fa fa-pencil"></i></div>
                     </td>
                 </tr>`;

        return template;
    }

    function renderList(items)  {
        let html = '';
        let num = 1;
        for (const i in items) {
            html = html + templateRow`${items[i]}${num++}`
        }
        $('#item-list').html(html)
    }

    $.ajax({
        url: '/',
        method: 'post',
        dataType: 'json'
    }).done(function (result) {
        items = result;
        renderList(items)
    });

    $('table').on('click', '[data-edit]', function () {
        const id = this.dataset.edit;
        const modal = $('#itemForm');
        const action = 'update';
        let form = $('#form-item');
        let item = items.find(item => item.id == id);

        $(form).trigger('reset');
        validator.resetForm();
        $(form).attr('action', '/update/' + id);

        for (const field in item) {
            if (field === 'image') {
                $('#'+field).attr('src', '/images/' + item[field]);
            } else {
                $('#'+field).val(item[field]);
            }
        }
        $(modal).modal('toggle')
    })

    $('#open-form').on('click', function () {
        let form = $('#form-item')[0];
        let modal = $('#itemForm');
        $(form).attr('action', '/create');
        $(form).trigger('reset');
        validator.resetForm();
        $(modal).modal('toggle');
    });

    $('table').on('click', '[data-delete]', function () {
        const id = this.dataset.delete;

        $.ajax({
            url: '/delete/'+id,
            method: 'post',
            dataType: 'json'
        }).done(function () {
            items = items.filter(item => item.id !== id)
            renderList(items)
        })
    });

})($);
