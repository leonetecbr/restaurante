const load = $('#load'), loadError = $('#load-error'), divManageTable = $('#manage-table')
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
let lastId

tooltipTriggerList.map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl))

$('.needs-validation').on('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()
    }

    $(this).addClass('was-validated')
})

$('#show-pass').on('click', () => {
    const passwordInput = $('#password')

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

$('.btn-edit-value').on('click', function () {
    const productId = $(this).data('product-id'), modal = $('#edit-value'), form = $('#form-edit')
    let url

    $('#name-edit').val($('#name-' + productId).html())
    $('#value-edit').val($('#value-' + productId).data('value'))
    url = form.data('action') + '/' + productId
    form.attr('action', url)
    new bootstrap.Modal(modal).show()
})

$('.btn-edit-capacity').on('click', function () {
    const tableId = $(this).data('table-id'), modal = $('#edit-capacity'), form = $('#form-edit')
    let url

    $('#table-edit').val(tableId)
    $('#capacity-edit').val($('#capacity-' + tableId).data('capacity'))
    url = form.data('action') + '/' + tableId
    form.attr('action', url)
    new bootstrap.Modal(modal).show()
})

$('#btn-new-table').on('click', () => {
    const modal = $('#new-table')
    new bootstrap.Modal(modal).show()
})

$('#manage-table-close').on('click',  () => divManageTable.addClass('d-none'))

$('#period-payment').on('change', () => {
    $('#form-select').trigger('submit')
})

$('.btn-detail-table').on('click', function () {
    getDetails('table', $(this).data('table-id'))
})

$('.btn-manage-table').on('click', function () {
    const tableId = $(this).data('table-id'), divManage = $('#manage'), btnManage = $('#btn-manage')

    load.removeClass('d-none')
    loadError.addClass('d-none')
    divManage.addClass('d-none')
    btnManage.addClass('d-none')

    $('#manage-table-id').html(tableId)

    divManageTable.removeClass('d-none')
    axios.get(apiDetails.replace('0', tableId))
        .then(function ({data}) {
            load.addClass('d-none')
            divManage.removeClass('d-none')
            btnManage.removeClass('d-none')

            const productsHTML = generateHTML(data, tableId, false)
            const btnVacantTable =  $('#btn-vacant-table')

            if (typeof data[0] === 'undefined') {
                $('#btn-close-bill').addClass('d-none')
                btnVacantTable.removeClass('d-none')
                btnVacantTable.attr('href', btnVacantTable.data('href') + '/' + tableId)
            }
            else {
                btnVacantTable.addClass('d-none')
                $('#btn-close-bill').removeClass('d-none')
            }
            $('#products').html(productsHTML)
            $('#total-products').html(data.total)
        })
        .catch(function (error) {
            load.addClass('d-none')
            divManage.addClass('d-none')
            loadError.removeClass('d-none')
            console.log(error)
        })
})

$('.btn-detail-order').on('click', function () {
    getDetails('order', $(this).data('order-id'))
})

function getDetails(typeData, itemId) {
    const modal = $('#detail-' + typeData), divDetail = $('#detail')

    load.removeClass('d-none')
    loadError.addClass('d-none')
    divDetail.addClass('d-none')

    $('#detail-' + typeData + '-id').html(itemId)
    new bootstrap.Modal(modal).show()

    axios.get(apiDetails.replace('0', itemId))
        .then(function ({data}) {
            load.addClass('d-none')
            divDetail.removeClass('d-none')
            let productsHTML = (typeData === 'table') ? generateHTML(data, itemId) : generateHTML(data)
            $('#products').html(productsHTML)
            $('#total-products').html(data.total)
        })
        .catch(function (error) {
            load.addClass('d-none')
            divDetail.addClass('d-none')
            loadError.removeClass('d-none')
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

function generateHTML(items, tableId = false, links = true) {
    let itemsHTML = ''

    if (typeof items[0] === 'undefined') itemsHTML = '<li class="list-group-item text-muted text-center">Não há produtos nessa mesa!</li>'
    else {
        for (let i = 0; typeof items[i] !== 'undefined'; i++) {
            const item = items[i]
            itemsHTML += `<li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-1">
                                <span class="badge bg-primary rounded-pill me-1">${item['quantity']}</span>`

            if (links) itemsHTML +=  `<a href="${productItem}#product-${item.id}" class="text-decoration-none">${item.name}</a>`
            else itemsHTML += item.name

            itemsHTML +=    `</h6>
                            <small class="text-muted">${item['unitaryValue']}</small>
                        </div>
                        <div>
                            <span class="text-muted me-2">${item['value']}</span>`

            if (tableId) {
                itemsHTML += `<a class="text-danger fw-bold text-decoration-none"
                                 href="${deleteItem}/${tableId}/${item.id}">
                                <i class="bi bi-x"></i>
                              </a>`
            }

            itemsHTML += `</div>
                        </li>`
        }
    }

    return itemsHTML
}

function hashChange() {
    let hash = window.location.hash

    if (lastId) {
        $(lastId).removeClass('table-primary')
    }

    if (hash.startsWith('#product-') || hash.startsWith('#table-')) {
        if (window.location.hash) {
            let id = window.location.hash
            $(id).addClass('table-primary')
            lastId = id
        }
    } else {
        lastId = undefined
    }
}

$(window).on('hashchange', function () {
    hashChange()
})

hashChange()
