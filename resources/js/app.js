const passwordInput = $('#password'), btnEditValue = $('.btn-edit-value'), btnEditCapacity = $('.btn-edit-capacity')
const btnNewTable = $('#btn-new-table')
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})

$('.needs-validation').on('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()
    }

    $(this).addClass('was-validated')
})

$('#show-pass').on('click', () => {
    if (passwordInput.attr('type') === 'password') {
        passwordInput.attr('type', 'text')
        $('#icon-show-pass').removeClass('bi-eye-fill').addClass('bi-eye-slash-fill')
        $('#text-show-pass').html('Esconder')
    } else {
        passwordInput.attr('type', 'password')
        $('#icon-show-pass').removeClass('bi-eye-slash-fill').addClass('bi-eye-fill')
        $('#text-show-pass').html('Mostrar')
    }
})

btnEditValue.on('click', function () {
    const productId = $(this).data('product-id'), modal = $('#edit-modal'), form = $('#form-edit')
    let url
    $('#name-edit').val($('#name-'+productId).html())
    $('#value-edit').val($('#value-'+productId).data('value'))
    url = form.data('action') + '/' + productId
    form.attr('action', url)
    new bootstrap.Modal(modal).show()
})

btnEditCapacity.on('click', function () {
    const tableId = $(this).data('table-id'), modal = $('#edit-modal'), form = $('#form-edit')
    let url
    $('#table-edit').val(tableId)
    $('#capacity-edit').val($('#capacity-'+tableId).data('capacity'))
    url = form.data('action') + '/' + tableId
    form.attr('action', url)
    new bootstrap.Modal(modal).show()
})

btnNewTable.on('click', function (){
    const modal = $('#new-modal')
    new bootstrap.Modal(modal).show()
})

$('#period-payment').on('change', () => {
    $('#form-select').trigger('submit')
})
