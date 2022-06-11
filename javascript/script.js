const searchRestaurantName = document.querySelector('#search-restaurants')
const searchRestaurantCategory = document.querySelector('#search-restaurant-by-category')
const searchRestaurantCategorybtn = document.querySelector('#btn-category')
const favoriteRestaurant = document.querySelector('#favorite-rest')


const searchDishRestaurant = document.querySelector('#search-dishes')

const dishBuyBtns = document.querySelectorAll('#dishes button')

const confirmOrder = document.querySelector('#confirm-order-now')
console.log(confirmOrder)

var total;


const encodeForAjax = function (data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

if(confirmOrder){
    confirmOrder.addEventListener('click', async function(){
        if(window.confirm("Do you want to confirm your order?")){
        var url = "../api/api_order.php";
    
    console.log(total)

    var final = {price: total}
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: encodeForAjax(final)
        }).then(final =>  final.json());
        } else{
            e.preventDefault()
            e.currentTarget.parentElement.parentElement.remove()
            updateTotal()
        }
    })
    
}


function attachBuyEvents(){
    for(const button of dishBuyBtns)
        button.addEventListener('click', function(e){
            const div = this.parentElement
            const price = div.querySelector('#dish-price').textContent
            console.log(price)
            const name  = div.querySelector('#dish-name').textContent
            console.log(name)
            const quantity = div.querySelector('.quantity').value
            console.log(quantity)

            const row = document.querySelector('#cart table tr[data-id="${id}"]')
            console.log(row)
        


            if (row) updateRow(row, price, quantity)
            else addRow(name, price, quantity)

            updateTotal()
        })
}

attachBuyEvents()




function addRow(name, price, quantity){
    const table = document.querySelector('#cart table')
    const row = document.createElement('tr')

    const nameCell = document.createElement('td')
    nameCell.textContent = name

    

    const quantityCell = document.createElement('td')
    quantityCell.textContent = quantity

    const priceCell = document.createElement('td')
    priceCell.textContent = price

    const totalCell = document.createElement('td')
    totalCell.textContent = price * quantity

    const deleteCell = document.createElement('td')

    deleteCell.classList.add('delete')
    deleteCell.innerHTML='<a href="">X</a>'
    deleteCell.querySelector('a').addEventListener('click', function(e){
        e.preventDefault()
        e.currentTarget.parentElement.parentElement.remove()
        updateTotal()
    })

    row.appendChild(nameCell)
    row.appendChild(quantityCell)
    row.appendChild(priceCell)
    row.appendChild(totalCell)
    row.appendChild(deleteCell)

    table.appendChild(row)
}

function updateRow(row, price, quantity){
    const quantityCell = row.querySelector('td:nth-child(2)')
    const totalCell = row.querySelector('td:nth-child(4)')
}

function updateTotal(){
    const rows = document.querySelectorAll('#cart table > tr')
    const values = [...rows].map(r =>parseFloat(r.querySelector('td:nth-child(4)').textContent, 10))
    total = values.reduce((t, v) => t+v, 0)
    document.querySelector('#cart table tfoot th:last-child').textContent = total
}

if(favoriteRestaurant){
    favoriteRestaurant.addEventListener('click', async function(){
        console.log(this.value)
        console.log(restaurantId)
    })
}


if(searchRestaurantName){
    searchRestaurantName.addEventListener('input', async function(){
    const response = await fetch ('../api/api_restaurants.php?search=' + this.value + '&mode=name')
    console.log(this.value)
    const restaurants = await response.json()
    console.log(restaurants)

    const section = document.querySelector('#restaurants')
    section.innerHTML = ''

    for(const restaurant of restaurants){
        const div = document.createElement('div')
        div.className = 'rest_items'
        const span = document.createElement('span')
        span.className = 'caption'
        const img = document.createElement('img')
   

        const spanDiv = document.createElement('div')
        const spanA = document.createElement('a')
        spanA.href = "/../pages/restaurant.php?id=" + restaurant['RestaurantId']
        const spanP = document.createElement('p')

        spanDiv.className = 'rest_review'


        spanDiv.textContent = restaurant['Review']


        img.src = '../images/restaurants/thumbs_small/' + restaurant['ImageId'] + '.jpg'
        spanP.textContent = restaurant['name']
        spanA.textContent = restaurant['RestaurantName']
        
        span.appendChild(spanA)
        span.appendChild(spanDiv)
        span.appendChild(spanP)

        div.appendChild(img)
        div.appendChild(span)

        section.appendChild(div)

    }

    })
}

if(searchRestaurantCategory){
    searchRestaurantCategory.addEventListener('input', async function(){
        const response = await fetch ('../api/api_restaurants.php?search=' + this.value + '&mode=category')
        const restaurants = await response.json()
        console.log(this.value)

        const section = document.querySelector('#restaurants')
        section.innerHTML = ''

        for(const restaurant of restaurants){
            const div = document.createElement('div')
            div.className = 'rest_items'
            const span = document.createElement('span')
            span.className = 'caption'
            const img = document.createElement('img')
   

            const spanDiv = document.createElement('div')
            const spanA = document.createElement('a')
            spanA.href = "/../pages/restaurant.php?id=" + restaurant['RestaurantId']
            const spanP = document.createElement('p')

            spanDiv.className = 'rest_review'


            spanDiv.textContent = restaurant['Review']


            img.src = '../images/restaurants/thumbs_small/' + restaurant['ImageId'] + '.jpg'
            spanP.textContent = restaurant['name']
            spanA.textContent = restaurant['RestaurantName']
            
            span.appendChild(spanA)
            span.appendChild(spanDiv)
            span.appendChild(spanP)

            div.appendChild(img)
            div.appendChild(span)

            section.appendChild(div)
        }
    })
}

if(searchDishRestaurant){
    searchDishRestaurant.addEventListener('input', async function(){
        const response = await fetch ('../api/api_dishes.php?search=' + this.value)
        console.log(this.value)
        const dishes = await response.json()
        console.log(dishes)

        const firstDiv = document.querySelector('.RestaurantCategory')
        firstDiv.innerHTML = ''
    
        for(const dish of dishes){
            const h2 = document.createElement('h2')
            const secondDiv = document.createElement('div')
            const h6 = document.createElement('h6')
            const p = document.createElement('p')
            const img = document.createElement('img')
            const form = document.createElement('form')
            const button = document.createElement('button')

            h6.textContent = dish['Name'] + ' / ' + '€' + dish['Price']
            p.textContent = dish['Ingredients']
            img.src='../images/dishes/thumbs_small/' + dish['ImageId'] + '.jpg'
            h2.textContent = dish['CategoryName']
            button.textContent = 'Purchase'


            form.appendChild(button)
            secondDiv.appendChild(h6)
            secondDiv.appendChild(img)
            secondDiv.appendChild(p)
            secondDiv.appendChild(form)

            firstDiv.appendChild(h2)
            firstDiv.appendChild(secondDiv)
        }
    })
}   