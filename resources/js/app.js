const load = $('#load'), loadError = $('#load-error'), divManageTable = $('#manage-table'), formEdit = $('#form-edit')
const closeBill = $('#close-bill'), payBill = $('#pay-bill')
const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
let lastId, manageTableId, valueTotal, peoplesPay, sum = 0

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

$('#edit-value').on('show.bs.modal', function (e) {
    const data = e.relatedTarget, url = data.getAttribute('data-action'),
        productId = data.getAttribute('data-product-id'), value = data.getAttribute('data-value')
    let name = $('#name-' + productId).html()

    name = (name.toUpperCase() === name) ? name : name.toLowerCase()

    $('#name-product').html(name)
    $('#value-product').val(value)
    formEdit.attr('action', url)
})

$('#edit-capacity').on('show.bs.modal', function (e) {
    const data = e.relatedTarget, tableId = data.getAttribute('data-table-id'),
        url = data.getAttribute('data-action'), capacity = data.getAttribute('data-capacity')

    $('#edit-table-id').html(tableId)
    $('#capacity-table').val(capacity)
    formEdit.attr('action', url)
})

$('#delete-table').on('show.bs.modal', function (e) {
    const data = e.relatedTarget, tableId =  data.getAttribute('data-table-id'),
        url =  data.getAttribute('data-href')

    $('#delete-table-id').html(tableId)
    $('#btn-confirm-delete').attr('href', url)
})

$('#manage-table-close').on('click',  () => divManageTable.addClass('d-none'))

$('#period-payment').on('change', () => $('#form-select').trigger('submit'))

$('#detail-table').on('show.bs.modal',  (e) => {
    getDetails('table', e.relatedTarget.getAttribute('data-table-id'))
})

$('#detail-order').on('show.bs.modal', (e) => {
    getDetails('order', e.relatedTarget.getAttribute('data-order-id'))
})

$('.btn-manage-table').on('click', function () {
    const tableId = $(this).data('table-id'), divManage = $('#manage'), btnManage = $('#btn-manage')

    window.location = '#manage-table'

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

            const productsHTML = generateDetailsHTML(data, false, false)
            const btnVacantTable =  $('#btn-vacant-table')

            manageTableId = tableId

            if (typeof data[0] === 'undefined') {
                $('#btn-close-bill').addClass('d-none')
                btnVacantTable.removeClass('d-none')
                btnVacantTable.attr('href', btnVacantTable.data('href') + '/' + tableId)
            }
            else {
                btnVacantTable.addClass('d-none')
                $('#btn-close-bill').removeClass('d-none')
                valueTotal = data.sum
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

$('#add-products').on('show.bs.modal',  () => {
    const productsJSON = $('#products-add-json'), formAddProducts = $('#form-add-products')
    $('#add-table-id').html(manageTableId)
    $('#form-product').removeClass('was-validated').trigger('reset')
    formAddProducts.attr('action', formAddProducts.data('action') + '/' + manageTableId)
    productsJSON.val(productsJSON.attr('data-value'))
    sum = 0
    $('#total-add-products').html(currency(sum))
    $('#products-add').html(generateAddHTML(null))
})

$('#form-product').on('submit', function (e){
    if (!this.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()
        $(this).addClass('was-validated')
    } else {
        e.preventDefault()
        $(this).addClass('was-validated')
        const option = $('#add-product-id :selected'), productsJSON = $('#products-add-json'), productId = option.val(),
            name = option.html(), valueUnitary = parseFloat(option.data('value')), productsAdd = $('#products-add')
        let products = JSON.parse(productsJSON.val()), quantity = parseInt($('#add-product-quantity').val())

        if (isZeroValues(products)) {
            $('#btn-submit-add').prop('disabled', false)
            productsAdd.html('')
        }

        products[productId] += quantity;

        productsJSON.val(JSON.stringify(products))

        if (products[productId] === quantity) productsAdd.append(generateAddHTML(name, valueUnitary, quantity, productId))
        else{
            sum += quantity * valueUnitary

            $('#products-add-' + productId + '-quantity').html(products[productId])
            $('#products-add-' + productId + '-value').html(currency(products[productId] * valueUnitary))
        }

        $('.products-add-delete').on('click', handleClickDelete)

        $('#total-add-products').html(currency(sum))

        $(this).removeClass('was-validated').trigger('reset')
    }
})

closeBill.on('show.bs.modal',  () => {
    $('#form-close-bill').removeClass('was-validated').trigger('reset')
    $('#close-table-id').html(manageTableId)
})

$('#form-close-bill').on('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault()
        e.stopPropagation()
        $(this).addClass('was-validated')
    } else{
        e.preventDefault()
        $(this).addClass('was-validated')

        peoplesPay = parseInt($('#quantity-people-table').val())
        $('#pay-table-id').html(manageTableId)

        bootstrap.Modal.getInstance(closeBill).hide()
        new bootstrap.Modal(payBill).show()
    }
})

payBill.on('show.bs.modal', () => {
    const formPayBill = $('#form-pay-bill')
    let value = parseFloat((valueTotal/peoplesPay).toFixed(2))
    let HTML = generatePayHTML()
    $('#method-pay-bill').html(HTML)
    $('#value-pay').html(currency(value))
    formPayBill.removeClass('was-validated')
    formPayBill.attr('action', formPayBill.data('action') + '/' + manageTableId)
})

$(window).on('scroll', () => {
    if ($(window).scrollTop() > 62) {
        $('body').css('padding-top', 62)
    } else {
        $('body').css('padding-top', $(window).scrollTop())
    }
})

$(window).on('hashchange',  () => hashChange())

function handleClickDelete(){
    const productsJSON = $('#products-add-json'), id = $(this).data('product-id')
    let products = JSON.parse(productsJSON.val())

    products[id] = 0
    sum -= parseFloat($('#products-add-'+ id +'-value').data('value'))

    productsJSON.val(JSON.stringify(products))
    $('#total-add-products').html(currency(sum))
    $('#products-add-' + id).remove()

    if (isZeroValues(products)) {
        $('#btn-submit-add').prop('disabled', true)
        $('#products-add').html(generateAddHTML(null))
    }
}

function getDetails(typeData, itemId) {
    const divDetail = $('#detail')

    load.removeClass('d-none')
    loadError.addClass('d-none')
    divDetail.addClass('d-none')

    $('#detail-' + typeData + '-id').html(itemId)

    axios.get(apiDetails.replace('0', itemId))
        .then(function ({data}) {
            load.addClass('d-none')
            divDetail.removeClass('d-none')
            let productsHTML = (typeData === 'table') ? generateDetailsHTML(data, itemId) : generateDetailsHTML(data)
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


function generateDetailsHTML(items, tableId = false, links = true) {
    let HTML = ''

    if (typeof items[0] === 'undefined') HTML = '<li class="list-group-item text-muted text-center">Não há produtos nessa mesa!</li>'
    else {
        for (let i = 0; typeof items[i] !== 'undefined'; i++) {
            const item = items[i]
            HTML += `<li class="list-group-item d-flex justify-content-between lh-sm">
                        <div>
                            <h6 class="my-1">
                                <span class="badge bg-primary rounded-pill me-1">${item['quantity']}</span>`

            if (links) HTML += `<a href="${productItem}#product-${item.id}" class="text-decoration-none">${item.name}</a>`
            else HTML += item.name

            HTML += `</h6>
                            <small class="text-muted">${item['unitaryValue']}</small>
                        </div>
                        <div>
                            <span class="text-muted me-2">${item['value']}</span>`

            if (tableId) {
                HTML += `<a class="text-danger fw-bold text-decoration-none"
                                 href="${deleteItem}/${tableId}/${item.id}">
                                <i class="bi bi-x"></i>
                              </a>`
            }

            HTML += `</div>
                        </li>`
        }
    }

    return HTML
}

function generateAddHTML(name, valueUnitary = null, quantity = null, id = null) {
    let HTML, value = quantity * valueUnitary

    sum += value

    if (name === null) HTML = '<li class="list-group-item text-muted text-center">Ainda não há produtos para adicionar!</li>'
    else {
        HTML = `<li class="list-group-item d-flex justify-content-between lh-sm" id="products-add-${id}">
                    <div>
                        <h6 class="my-1">
                            <span class="badge bg-primary rounded-pill me-1" id="products-add-${id}-quantity">
                                ${quantity}
                            </span>
                            ${name}
                        </h6>
                        <small class="text-muted">${currency(valueUnitary)}</small>
                    </div>
                    <div>
                        <span class="text-muted me-2" id="products-add-${id}-value" data-value=${value}>${currency(value)}</span>
                        <a class="text-danger fw-bold text-decoration-none products-add-delete" data-product-id="${id}"
                            href="#delete-product">
                            <i class="bi bi-x"></i>
                        </a>
                    </div>
                </li>`
    }

    return HTML
}

function generatePayHTML(){
    let HTML = ''
    for (let i = 1; i <= peoplesPay; i++) {
        HTML += `<div class="mt-3">
                    <label for="method-${i}" class="form-label">Cliente ${i} pagou em(no)</label>
                    <select class="form-select" name="method[]" id="method-${i}" required>
                        <option disabled selected value="" class="d-none">Selecione ...</option>
                        <option value="dinheiro">Dinheiro</option>
                        <option value="débito">Débito</option>
                        <option value="crédito">Crédito</option>
                        <option value="pix">PIX</option>
                    </select>
                </div>`
    }
    return HTML
}

function currency(num){
    return num.toLocaleString('pt-br', {style: 'currency', currency: 'BRL'})
}

function isZeroValues(obj){
    const keys = Object.keys(obj)
    let result = true

    for (let i = 0; i < keys.length; i++){
        if (obj[keys[i]] !== 0){
            return false
        }
    }

    return result
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

hashChange()
