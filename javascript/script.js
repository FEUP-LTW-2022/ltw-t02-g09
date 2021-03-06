const searchRestaurantName = document.querySelector('#search-restaurants')
const searchRestaurantCategory = document.querySelector('#search-restaurant-by-category')
const searchRestaurantCategorybtn = document.querySelector('#btn-category')
const favoriteRestaurant = document.querySelector('#favorite-rest')


const searchDishRestaurant = document.querySelector('#search-dishes')

const dishBuyBtns = document.querySelectorAll('.RestaurantCategory button')

const confirmOrder = document.querySelector('#order-btn')
console.log(confirmOrder)

var total;

function addZeroes(num) {
    const dec = num.split('.')[1]
    const len = dec && dec.length > 2 ? dec.length : 2
    return Number(num).toFixed(len)
  }

const encodeForAjax = function (data) {
    return Object.keys(data).map(function (k) {
        return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
}

if(confirmOrder){
    confirmOrder.addEventListener('click', async function(){
        if(window.confirm("Do you want to confirm your order?")){
        const rows = document.querySelectorAll("#cart > table > tr");   
        var url1 = "../api/api_order.php";
        var url2 = "../api/api_dish_order.php";
    
    console.log(total)

    var final = {price: total}
    console.log(final)
    fetch(url1, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: encodeForAjax(final)
        }).then(final =>  final.json());
    for(const row of rows){
        console.log(row.children.item(2).textContent)
        console.log(row.firstChild.innerHTML)
        let quantity = {dishQuantity: row.children.item(2).textContent,
                        dishId:         row.firstChild.innerHTML};
        console.log(quantity);
        fetch(url2, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: encodeForAjax(quantity)
        })
}
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

            const id = div.getAttribute('dish-id')
            const price = div.querySelector('#dish-price').textContent
            const name  = div.querySelector('#dish-name').textContent
            const quantity = div.querySelector('.quantity').value

            const row = document.querySelector('#cart table tr[data-id="${id}"]')
        


            if (row) updateRow(row, price, quantity)
            else addRow(id, name, price, quantity)

            updateTotal()
        })
}

attachBuyEvents()




function addRow(id, name, price, quantity){
    const table = document.querySelector('#cart table')
    const row = document.createElement('tr')

    const idCell = document.createElement('td')
    idCell.classList.add('hide')
    idCell.textContent = id

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
    deleteCell.innerHTML='<a href=""><img src="../images/assets/delete-icon.jpeg" width="35" height="35"></a>'
    deleteCell.querySelector('a').addEventListener('click', function(e){
        e.preventDefault()
        e.currentTarget.parentElement.parentElement.remove()
        updateTotal()
    })

    row.appendChild(idCell)
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
    const values = [...rows].map(r =>parseFloat(r.querySelector('td:nth-child(5)').textContent, 10))
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
        const divA = document.createElement('a')
        divA.href = "/../pages/restaurant.php?id=" + restaurant['RestaurantId']
        const span = document.createElement('span')
        span.className = 'caption'
        const img = document.createElement('img')
   

        const spanA = document.createElement('a')
        spanA.href = "/../pages/restaurant.php?id=" + restaurant['RestaurantId']
        const spanP = document.createElement('p')


        img.src = '../images/restaurants/thumbs_small/' + restaurant['ImageId'] + '.jpg'
        spanP.textContent = restaurant['name']
        spanA.textContent = restaurant['RestaurantName']
        
        span.appendChild(spanA)
        span.appendChild(spanP)

        divA.appendChild(img)

        div.appendChild(divA)
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
            const divA = document.createElement('a')
            divA.href = "/../pages/restaurant.php?id=" + restaurant['RestaurantId']
            const span = document.createElement('span')
            span.className = 'caption'
            const img = document.createElement('img')
   
            const spanA = document.createElement('a')
            spanA.href = "/../pages/restaurant.php?id=" + restaurant['RestaurantId']
            const spanP = document.createElement('p')

            img.src = '../images/restaurants/thumbs_small/' + restaurant['ImageId'] + '.jpg'
            spanP.textContent = restaurant['name']
            spanA.textContent = restaurant['RestaurantName']
            
            span.appendChild(spanA)
            span.appendChild(spanP)

            divA.appendChild(img)

            div.append(divA)    
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

        const section = document.querySelector('#restaurants')
        section.innerHTML = ''

        const firstDiv = document.createElement('div')
        firstDiv.setAttribute('class', '.restaurant-container')
        
        const categoryDiv = document.createElement('div')
        categoryDiv.setAttribute('id', 'category-title')
        const h2 = document.createElement('h2')
        h2.setAttribute('class', 'title')

        h2.textContent = dishes[0]['CategoryName']
        
        categoryDiv.appendChild(h2)
        firstDiv.appendChild(categoryDiv)

        let nameCategory = dishes[0]['CategoryName'];

        for(const dish of dishes){
            if(dish['CategoryName'] != nameCategory){
                const categoryDiv = document.createElement('div')
                categoryDiv.setAttribute('id', 'category-title')

                const h2 = document.createElement('h2')
                h2.setAttribute('class', 'title')

                h2.textContent = dish['CategoryName']
                nameCategory = dish['CategoryName'];

                categoryDiv.appendChild(h2)
                firstDiv.appendChild(categoryDiv)
            }

            const restaurantDiv = document.createElement('div')
            restaurantDiv.setAttribute('class', 'RestaurantCategory')

            const img = document.createElement('img')
            img.src='../images/dishes/thumbs_small/' + dish['ImageId'] + '.jpg'

            const secondDiv = document.createElement('div')

            const h3 = document.createElement('h3')
            const pPrice = document.createElement('p')
            const pIngredients = document.createElement('p')

            h3.setAttribute('id', 'dish-name')
            pPrice.setAttribute('id', 'dish-price')
            pIngredients.setAttribute('class', 'DishIngredients')

            h3.textContent = dish['Name']

            pPrice.textContent = dish['Price'].toLocaleString("en",{useGrouping: false,minimumFractionDigits: 2})
            pIngredients.textContent = dish['Ingredients']

            secondDiv.appendChild(h3)
            secondDiv.appendChild(pPrice)
            secondDiv.appendChild(pIngredients)
            

            const button = document.createElement('button')
            const input = document.createElement('input')

            input.setAttribute('type', 'number')
            input.setAttribute('value', '1')
            input.setAttribute('min', '1')
            input.setAttribute('class', 'quantity')

            button.setAttribute('id','register-btn')
            button.textContent = 'Purchase'

            restaurantDiv.appendChild(img)
            restaurantDiv.appendChild(secondDiv)
            restaurantDiv.appendChild(input)
            restaurantDiv.appendChild(button)

            firstDiv.appendChild(restaurantDiv)

            section.appendChild(firstDiv)
        }
    })
}   