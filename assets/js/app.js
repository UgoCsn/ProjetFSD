
function redirect(link){
    if(link === 'CGU'){
        window.location.href = `http://127.0.0.1:8000/${link}`;
    }

    else if (link === 'CGV'){
        window.location.href = `http://127.0.0.1:8000/${link}`;
    }

    else if (link === 'confidential'){
        window.location.href = `http://127.0.0.1:8000/${link}`;
    }
    else if (link === 'legal'){
        window.location.href = `http://127.0.0.1:8000/${link}`;
    }

    else {
        window.location.href = `http://127.0.0.1:8000/`;
    }
}

function toggleMeubles() {
    let side = document.getElementById("toggleMeuble");
    let background = document.getElementById("background");
    if (side.style.display === "none" && background.style.display === "none") {
        side.style.display = "block";
        background.style.display = "block";
    } else {
        side.style.display = "none";
        background.style.display = "none";
    }
}

function toggleAccessoires() {
    let side = document.getElementById("toggleAccessoires");
    let background = document.getElementById("background");
    if (side.style.display === "none" && background.style.display === "none") {
        side.style.display = "block";
        background.style.display = "block";
    } else {
        side.style.display = "none";
        background.style.display = "none";
    }
}

function redirectLogin() {
    window.location.href = `http://127.0.0.1:8000/login`
}

function redirectRegister() {
    window.location.href = `http://127.0.0.1:8000/register`
}

    document.querySelector('#searchBar').addEventListener('onChange', (e) => {


        let userSearch = e.target.value;

        fetch(`/search/${ userSearch }`)
            .then( res => res.json() )
            .then( data => {

                /*

                    data = [
                        {
                            name: 'chaise longue',
                            price: 59,
                            category: {
                                name: 'chaises'
                            }
                        }
                    ]

                */


                let productsList = document.querySelector('#productsList')
                productsList.innerHTML = '';

                for( let i = 0; i < data; i++ )
                {
                    let product = data[i]; // { id: 1, name: 'chaise' }

                    let article = document.createElement('article');

                    article.classList.add('product', '...');

                    article.innerHTML = `
                <a href="/product/detail/${ product.id }">
                        <h2>${ product.name }</h2>
                        <img src="../../public/uploads/${product.image}" alt="image d'un article">
                        <p>${product.description}</p>
                        <p>${product.price/100}€</p>
                </a>
            `;
                    productsList.append(article);
                }
            })

    })


