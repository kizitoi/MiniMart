<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container py-4">
    <div class="mb-3">
        <label for="shop-selector" class="form-label">Select Shop</label>
        <select id="shop-selector" class="form-select">
            <option value="">-- Choose a Shop --</option>
            <?php foreach ($shops as $shop): ?>
                <option value="<?= $shop['id'] ?>"><?= esc($shop['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Category Search -->
    <input type="text" id="category-search" class="form-control mb-3" placeholder="Search categories...">

    <!-- Category Grid -->
    <div id="category-grid" class="d-flex flex-wrap gap-3 mb-4"></div>

    <!-- Item Search -->
    <input type="text" id="item-search" class="form-control mb-3" placeholder="Search items...">

    <!-- View Switch -->
    <div class="mb-3">
        <button id="grid-view" class="btn btn-primary btn-sm">Grid View</button>
        <button id="list-view" class="btn btn-outline-secondary btn-sm">List View</button>
    </div>

    <!-- Item Grid/List -->
    <div id="item-grid" class="row row-cols-2 row-cols-md-3 g-3"></div>
</div>

<script>
    let isGrid = true;

    document.getElementById('grid-view').addEventListener('click', () => {
        isGrid = true;
        document.getElementById('item-grid').className = 'row row-cols-2 row-cols-md-3 g-3';
    });

    document.getElementById('list-view').addEventListener('click', () => {
        isGrid = false;
        document.getElementById('item-grid').className = 'list-group';
    });

    document.getElementById('shop-selector').addEventListener('change', function () {
        const shopId = this.value;
        fetch(`/pos/getCategoriesByShop/${shopId}`)
            .then(res => res.json())
            .then(categories => {
                const grid = document.getElementById('category-grid');
                grid.innerHTML = '';
                categories.forEach(cat => {
                    grid.innerHTML += `
                        <button class="btn category-btn" data-id="${cat.id}">
                            <img src="/uploads/categories/${cat.image}" width="60" height="60"><br>
                            <strong>${cat.name}</strong>
                        </button>`;
                });
            });
    });

    document.addEventListener('click', function (e) {
        if (e.target.closest('.category-btn')) {
            const categoryId = e.target.closest('.category-btn').dataset.id;
            fetch(`/pos/getItemsByCategory/${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    renderItems(data);
                });
        }
    });

    function renderItems(items) {
        const grid = document.getElementById('item-grid');
        grid.innerHTML = '';
        items.forEach(item => {
            if (isGrid) {
                grid.innerHTML += `
                    <div class="col">
                        <div class="card text-center border-0 shadow-sm">
                            <img src="/uploads/items/${item.photo}" class="card-img-top" style="height: 120px; object-fit: cover;">
                            <div class="card-body">
                                <h6>${item.name}</h6>
                                <p class="text-muted">$${parseFloat(item.unit_price).toFixed(2)}</p>
                            </div>
                        </div>
                    </div>`;
            } else {
                grid.innerHTML += `
                    <div class="list-group-item d-flex align-items-center">
                        <img src="/uploads/items/${item.photo}" class="me-3" width="60" height="60" style="object-fit: cover;">
                        <div>
                            <h6 class="mb-0">${item.name}</h6>
                            <small class="text-muted">$${parseFloat(item.unit_price).toFixed(2)}</small>
                        </div>
                    </div>`;
            }
        });
    }

    document.getElementById('category-search').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#category-grid button').forEach(btn => {
            const text = btn.innerText.toLowerCase();
            btn.style.display = text.includes(query) ? 'inline-block' : 'none';
        });
    });

    document.getElementById('item-search').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('#item-grid .card, #item-grid .list-group-item').forEach(el => {
            const name = el.innerText.toLowerCase();
            el.style.display = name.includes(query) ? 'block' : 'none';
        });
    });
</script>

<?= $this->endSection() ?>
