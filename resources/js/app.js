const passwordInput = $('#password'), btnEditValue = $('.btn-edit-value'), btnEditCapacity = $('.btn-edit-capacity')
const btnNewTable = $('#btn-new-table'), btnDetailTable = $('.detail-table'), loadDetail =  $('#load-detail')
const loadDetailError =  $('#load-detail-error'), detailDiv = $('#detail')
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
})
let lastId

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

btnNewTable.on('click',  () => {
    const modal = $('#new-modal')
    new bootstrap.Modal(modal).show()
})

$('#period-payment').on('change', () => {
    $('#form-select').trigger('submit')
})

btnDetailTable.on('click', function () {
    loadDetail.removeClass('d-none')
    loadDetailError.addClass('d-none')
    detailDiv.addClass('d-none')
    const modal = $('#detail-modal')
    let tableId = $(this).data('table-id'), url
    $('#detail-table-id').html(tableId)
    new bootstrap.Modal(modal).show()
    url = (window.location.href.endsWith('/')) ?
        window.location.href + tableId :
        window.location.href + '/' + tableId
    axios.get(url)
        .then(function ({data}) {
            loadDetail.addClass('d-none')
            detailDiv.removeClass('d-none')
            let productsHTML = ''
            for (let i = 0; typeof data[i] !== "undefined"; i++){
                productsHTML += `<li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-1">
                            <span class="badge bg-primary rounded-pill">${data[i]['quantity']}</span>
                            <a href="${productItem}#product-${data[i].id}" class="text-decoration-none">${data[i].name}</a>
                        </h6>
                        <small class="text-muted">${data[i]['unitaryValue']}</small>
                    </div>
                    <div>
                        <span class="text-muted me-2">${data[i]['value']}</span>
                        <a class="text-danger fw-bold pointer" href="${deleteItem}/${tableId}/${data[i].id}"><i class="bi bi-x"></i></a>
                    </div>
                </li>`
            }
            console.log(productsHTML)
            $('#products').html(productsHTML)
            $('#total-products').html(data.total)
        })
        .catch(function (error) {
            loadDetail.addClass('d-none')
            detailDiv.addClass('d-none')
            loadDetailError.removeClass('d-none')
            console.log(error)
        })
})

$(window).on('scroll', () => {
    if ($(window).scrollTop() > 62) {
        $('body').css('padding-top', 62)
    } else {
        $('body').css('padding-top', $(window).scrollTop())
    }
})

function hashChange(){
    if (lastId){
        $(lastId).removeClass('table-primary')
    }

    if (window.location.hash) {
        let id = window.location.hash
        $(id).addClass('table-primary')
        lastId = id
    }
}

$(window).on('hashchange', function() {
    hashChange()
})

hashChange()
