function encodeForAjax(data) {
  if (data == null) return null;
  return Object.keys(data).map(function(k){
    return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
  }).join('&');
}

function sendAjaxRequest(method, url, data, handler) {
  let request = new XMLHttpRequest();

  request.open(method, url, true);
  request.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
  request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  request.addEventListener('load', handler);
  request.send(encodeForAjax(data));
}

function toggleSearch() {
  const searchImage = document.querySelector('.search-icon img');
  const searchBar = document.getElementById('search-bar');
  const platformLinks = document.querySelector('.platform-links');

  if (searchBar.style.display === 'none' || searchBar.style.display === '') {
      // Show the search bar, hide the platform links
      searchBar.style.display = 'block';
      platformLinks.style.display = 'none';
      searchImage.style.display = 'none';
  } else {
      // Show the platform links, hide the search bar
      searchBar.style.display = 'none';
      platformLinks.style.display = 'flex';
      searchImage.style.display = 'block';
  }
}

function closeSearch() {
  const searchBar = document.getElementById('search-bar');
  const platformLinks = document.querySelector('.platform-links');
  const searchImage = document.querySelector('.search-icon img');

  searchBar.style.display = 'none';
  platformLinks.style.display = 'flex';
  searchImage.style.display = 'block';
}

function initializeChart(canvasId, labels, data, type = 'pie') {
const ctx = document.getElementById(canvasId).getContext('2d');

new Chart(ctx, {
    type: type,
    data: {
        labels: labels,
        datasets: [{
            label: 'Products Sold',
            data: data,
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function (tooltipItem) {
                        return tooltipItem.label + ': ' + tooltipItem.raw + ' Products';
                    }
                }
            }
        }
    }
});
}

document.addEventListener('DOMContentLoaded', () => {
    const addToCartButtons = document.querySelectorAll('.add-to-cart');

    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const productId = button.getAttribute('data-product-id');
            const userId = document.querySelector('meta[name="user-id"]').getAttribute('content');
            const quantityInput = document.querySelector(`#quantity-${productId}`);
            const quantity = parseInt(quantityInput.value, 10);

            // Debugging logs
            console.log('Product ID:', productId);
            console.log('User ID:', userId);
            console.log('Quantity:', quantity);

            // Validation checks
            if (!productId || !userId || isNaN(quantity) || quantity <= 0) {
                alert('Invalid product ID, user ID, or quantity.');
                return;
            }

            // Send the request to the server to add the item to the cart
            fetch(`/shopping-cart/add/${productId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ quantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    window.location.href = data.redirect;
                } else {
                    alert('Unexpected response format.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while adding the product to the cart.');
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.quantity-dropdown').forEach(dropdown => {
        dropdown.addEventListener('change', function() {
            // Submit the form containing this dropdown
            this.closest('.update-quantity-form').submit();
        });
    });
});


