const api = (url, data) => fetch(url, {
  method: data ? 'POST' : 'GET',
  headers: data ? { 'Content-Type': 'application/json' } : {},
  body: data ? JSON.stringify(data) : null
}).then(res => res.json());

let categories = [];
let activeCat = null;

async function loadCategories() {
  categories = await api('php/get_categories.php');
  const tabs = document.getElementById('tabsContainer');
  tabs.innerHTML = '';

  categories.forEach(cat => {
    const tab = document.createElement('div');
    tab.className = 'tab';
    tab.textContent = cat.name;
    tab.dataset.id = cat.id;

    const editIcon = document.createElement('span');
    editIcon.className = 'edit';
    editIcon.textContent = '✎';
    tab.appendChild(editIcon);

    const delIcon = document.createElement('span');
    delIcon.className = 'delete-cat';
    delIcon.textContent = '✕';
    tab.appendChild(delIcon);

    tabs.appendChild(tab);

    tab.onclick = () => selectCategory(cat.id);
    editIcon.onclick = e => { e.stopPropagation(); editCategory(cat); };
    delIcon.onclick = e => { e.stopPropagation(); deleteCategory(cat.id); };
  });

  if (categories.length) selectCategory(categories[0].id);
}

function selectCategory(id) {
  activeCat = id;
  document.querySelectorAll('.tab').forEach(t => t.classList.toggle('active', t.dataset.id == id));
  loadProducts(id);
}

document.getElementById('addCategoryBtn').onclick = async () => {
  const name = document.getElementById('newCategoryName').value.trim();
  if (!name) return;
  await api('php/add_category.php', { name });
  document.getElementById('newCategoryName').value = '';
  loadCategories();
};

async function editCategory(cat) {
  const name = prompt('Edit category name', cat.name);
  if (name) {
    await api('php/update_category.php', { id: cat.id, name });
    loadCategories();
  }
}

async function deleteCategory(id) {
  if (confirm('Delete this category and all its products?')) {
    await api('php/delete_category.php', { id });
    loadCategories();
  }
}

async function loadProducts(catId) {
  const prods = await api(`php/get_products.php?category_id=${catId}`);
  const container = document.getElementById('tablesContainer');
  container.innerHTML = '';

  const section = document.createElement('section');
  section.classList.add('active');
  section.dataset.cat = catId;
  section.innerHTML = `
    <h2 class="category-title">${categories.find(c => c.id == catId).name}</h2>
    <form id="prodForm-${catId}" class="prod-form">
      <input name="name" placeholder="Product Name" required>
      <input name="size" placeholder="Size (e.g. 500ml, 1kg)" required>
      <input name="quantity" type="number" min="0" placeholder="Qty" required>
      <input name="price" type="number" step="0.01" min="0" placeholder="Price" required>
      <button type="submit">Add</button>
    </form>
    <table>
      <thead><tr><th>Name</th><th>Size</th><th>Qty</th><th>Price</th><th>Actions</th></tr></thead>
      <tbody></tbody>
    </table>`;
  container.appendChild(section);

  const tbody = section.querySelector('tbody');

  prods.forEach(p => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td>${p.name}</td>
      <td>${p.size}</td>
      <td>
        <button class="decrease" data-id="${p.id}">−</button>
        <span class="qty" data-id="${p.id}">${p.quantity}</span>
        <button class="increase" data-id="${p.id}">+</button>
      </td>
      <td>${parseFloat(p.price).toFixed(2)}</td>
      <td>
        <button class="edit-prod" data-id="${p.id}">Edit</button>
        <button class="delete" data-id="${p.id}">Delete</button>
      </td>`;
    tbody.appendChild(tr);

    tr.querySelector('.increase').onclick = async () => {
      await api('php/update_quantity.php', { id: p.id, change: 1 });
      loadProducts(catId);
    };
    tr.querySelector('.decrease').onclick = async () => {
      await api('php/update_quantity.php', { id: p.id, change: -1 });
      loadProducts(catId);
    };
    tr.querySelector('.delete').onclick = async () => {
      await api('php/delete_product.php', { id: p.id });
      loadProducts(catId);
    };
    tr.querySelector('.edit-prod').onclick = () => editProduct(p);
  });

  const form = section.querySelector(`#prodForm-${catId}`);
  form.onsubmit = async e => {
    e.preventDefault();
    const fd = new FormData(form);
    await api('php/add_product.php', {
      category_id: catId,
      name: fd.get('name'),
      size: fd.get('size'),
      quantity: fd.get('quantity'),
      price: fd.get('price')
    });
    form.reset();
    loadProducts(catId);
  };
}

function editProduct(p) {
  const name = prompt('Product name', p.name);
  const size = prompt('Size', p.size);
  const quantity = prompt('Quantity', p.quantity);
  const price = prompt('Price', p.price);
  if (name != null && size != null && quantity != null && price != null) {
    api('php/update_product.php', { id: p.id, name, size, quantity, price })
      .then(() => loadProducts(activeCat));
  }
}

window.addEventListener('DOMContentLoaded', () => loadCategories());
