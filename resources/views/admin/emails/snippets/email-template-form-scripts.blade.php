<script>

    document.addEventListener('DOMContentLoaded', function () {
        const featuredProductLinks = document.querySelectorAll('.featuredProductLink');
        const body = document.querySelector('body');

        featuredProductLinks.forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();

                const slotId = e.currentTarget.dataset.slotId
                console.log('target', e.target);
                console.log('slotIDoutside', slotId);
                //Make post request to route product.list-as-json to get products
                //Add csrf token to the request

                fetch('/admin/hotel/{{$hotel->id}}/list-products-as-json', {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    method: 'post'
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        body.classList.add('overflow-hidden');
                        //Create a modal with the products
                        const modal = document.createElement('div');
                        modal.classList.add('fixed', 'top-0', 'left-0', 'w-full', 'h-full', 'bg-black/20', 'flex', 'justify-center', 'items-center');
                        modal.innerHTML = `
                                <div class="bg-white p-4 rounded-2xl h-[500px] overflow-scroll">
                                    <h2 class="text-xl font-bold">Select a product</h2>
                                    <ul>
                                        ${data.map(product => {
                            return `<li class="flex items-center justify-between border-b border-gray-300 p-2">
                                                   <img src="${product.image}" alt="${product.name}" class="w-12 h-12 object-cover rounded"/>
                                                    <div class="flex flex-col flex-grow pl-2">
                                                        <span class="text-left">${product.name}</span>
                                                    <div class="text-right">
                                                        <button data-product-id="${product.id}" class="addProduct bg-black text-white px-4 py-1 rounded-full">Add</button>
                                                    </div>
                                                    </div>
                                            </li>`
                        }).join('')}
                                    </ul>
                                </div>
                            `;
                        document.body.appendChild(modal);
                        //Add event listener to the add button

                        //If the outer modal background is clicked, remove the modal
                        modal.addEventListener('click', function (e) {
                            if (e.target === modal) {
                                modal.remove();
                                body.classList.remove('overflow-hidden');
                            }
                        });

                        setupAddProductButtons(slotId, modal)

                    });
            }, false);

        });

        const setupAddProductButtons = (slotId, modal) => {
            const addProductButtons = document.querySelectorAll('.addProduct');

            addProductButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    const productId = button.getAttribute('data-product-id');
                    //Make a post request to get the product as json
                    let productData = getProductAsJson(productId);

                    productData.then(data => {
                        console.log(data);
                        //Update the image and name of the product in the slot
                        const slot = document.querySelector(`[data-slot-id="${slotId}"]`);
                        console.log('slotId', slotId);
                        slot.innerHTML = `
                                            <img src="${data.image}" alt="${data.name}" class="w-full h-[150px] object-cover rounded-2xl"/>
                                            <p class="text-left">${data.name}</p>
                                            <strong>{{\App\Helpers\Money::lookupCurrencySymbol($hotel->user->currency)}}${data.price}</strong>
                                        `;
                        //Add the product id to the hidden input
                        const input = document.createElement('input');
                        input.setAttribute('type', 'hidden');
                        input.setAttribute('name', 'hotel_email[featured-products][]');
                        input.setAttribute('value', data.id);
                        slot.appendChild(input);
                        //Remove the modal
                        modal.remove();
                        body.classList.remove('overflow-hidden');
                        //Show the remove icon
                        const removeIcon = document.querySelector(`[data-remove-slot-id="${slotId}"`);
                        removeIcon.classList.remove('hidden');

                    })
                })
            });
        }


        const getProductAsJson = (productId) => {
            return fetch('/admin/product/' + productId + '/get-as-json', {
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                method: 'post'
            })
                .then(response => response.json())
                .then(data => {
                    return data;
                });

        }
    });

    const removeIcon = document.querySelectorAll('.remove-icon');

    removeIcon.forEach(icon => {
        icon.addEventListener('click', function (e) {
            e.preventDefault();
            const slotId = e.currentTarget.dataset.removeSlotId;
            const slot = document.querySelector(`[data-slot-id="${slotId}"]`);
            slot.innerHTML = `
                    <div class="flex  items-center justify-center border-darkGrey border rounded-2xl h-[150px] p-4">
                        <img src="/img/icons/plus.svg" alt="plus" class=" w-8 h-8"/>
                    </div>
                    <input type="hidden" name="hotel_email[featured-products][]" value="0">
                `;
            icon.classList.add('hidden');
        });
    });


</script>
