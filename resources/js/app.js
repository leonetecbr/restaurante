const passwordInput = $('#password'), btnEditValue = $('.edit-value-btn'), btnEditCapacity = $('.edit-capacity-btn')
const btnNewTable = $('#new-table-btn'), btnDetailTable = $('.detail-table-btn'), loadDetail =  $('#load-detail')
const loadDetailError =  $('#load-detail-error'), detailDiv = $('#detail'), btnDetailOrder = $('.detail-order-btn')
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
    const productId = $(this).data('product-id'), modal = $('#edit-value'), form = $('#form-edit')
    let url

    $('#name-edit').val($('#name-'+productId).html())
    $('#value-edit').val($('#value-'+productId).data('value'))
    url = form.data('action') + '/' + productId
    form.attr('action', url)
    new bootstrap.Modal(modal).show()
})

btnEditCapacity.on('click', function () {
    const tableId = $(this).data('table-id'), modal = $('#edit-capacity'), form = $('#form-edit')
    let url

    $('#table-edit').val(tableId)
    $('#capacity-edit').val($('#capacity-'+tableId).data('capacity'))
    url = form.data('action') + '/' + tableId
    form.attr('action', url)
    new bootstrap.Modal(modal).show()
})

btnNewTable.on('click',  () => {
    const modal = $('#new-table')
    new bootstrap.Modal(modal).show()
})

$('#period-payment').on('change', () => {
    $('#form-select').trigger('submit')
})

btnDetailTable.on('click', function () {
    getDetails('table', $(this).data('table-id'))
})

btnDetailOrder.on('click', function (){
    getDetails('order', $(this).data('order-id'))
})

function getDetails(typeData, itemId){
    const modal = $('#detail-' + typeData)
    let url, data = {}

    loadDetail.removeClass('d-none')
    loadDetailError.addClass('d-none')
    detailDiv.addClass('d-none')

    $('#detail-'+ typeData +'-id').html(itemId)
    new bootstrap.Modal(modal).show()
    url = window.location.href.split('#')[0] // Retira a hash
    url = (url.endsWith('/')) ?
        url + itemId :
        url + '/' + itemId

    axios.get(url)
        .then(function ({data}) {
            loadDetail.addClass('d-none')
            detailDiv.removeClass('d-none')
            let productsHTML = (typeData === 'table') ?  generateHTML(data, itemId) : generateHTML(data)
            $('#products').html(productsHTML)
            $('#total-products').html(data.total)
        })
        .catch(function (error) {
            loadDetail.addClass('d-none')
            detailDiv.addClass('d-none')
            loadDetailError.removeClass('d-none')
            console.log(error)
        })
}

$(window).on('scroll', () => {
    if ($(window).scrollTop() > 62) {
        $('body').css('padding-top', 62)
    } else {
        $('body').css('padding-top', $(window).scrollTop())
    }
})

function generateHTML(items, tableId = false){
    let itemsHTML = ''

    for (let i = 0; typeof items[i] !== "undefined"; i++){
        const item = items[i]
        itemsHTML += `<li class="list-group-item d-flex justify-content-between lh-sm">
                    <div>
                        <h6 class="my-1">
                            <span class="badge bg-primary rounded-pill">${item['quantity']}</span>
                            <a href="${productItem}#product-${item.id}" class="text-decoration-none">${item.name}</a>
                        </h6>
                        <small class="text-muted">${item['unitaryValue']}</small>
                    </div>
                    <div>
                        <span class="text-muted me-2">${item['value']}</span>`

        if (tableId) {
            itemsHTML += `<a class="text-danger fw-bold text-decoration-none" href="${deleteItem}/${tableId}/${item.id}">
                            <i class="bi bi-x"></i>
                          </a>`
        }

        itemsHTML += `</div>
                    </li>`
    }

    return itemsHTML
}

function hashChange() {
    let hash = window.location.hash

    if (lastId) {
        $(lastId).removeClass('table-primary')
    }

    if (hash.startsWith('#product-') || hash.startsWith('#table-')){
        if (window.location.hash) {
            let id = window.location.hash
            $(id).addClass('table-primary')
            lastId = id
        }
    } else{
        lastId = undefined
    }
}

$(window).on('hashchange', function() {
    hashChange()
})

hashChange()
