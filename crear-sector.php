<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Inventario</title>
</head>
<?php require_once "validacion-usuario.php"; ?>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background-color: #f5f5f5;
    color: #333;
    line-height: 1.6;
}

/* Header */
.header {
    background-color: #e0e0e0;
    border-bottom: 1px solid #ccc;
    padding: 0;
}

.nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    height: 50px;
}

.nav-left {
    display: flex;
    align-items: center;
}

.user-icon {
    font-size: 20px;
    padding: 8px;
    background-color: #ccc;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.nav-center {
    display: flex;
    gap: 20px;
}

.nav-item {
    text-decoration: none;
    color: #666;
    padding: 8px 12px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.nav-item:hover,
.nav-item.active {
    background-color: #d0d0d0;
    color: #333;
}

.nav-right {
    display: flex;
    align-items: center;
}

.theme-toggle {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    padding: 8px;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.theme-toggle:hover {
    background-color: #d0d0d0;
}

/* Container */
.container {
    display: flex;
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    gap: 20px;
    min-height: calc(100vh - 120px);
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    height: fit-content;
}

.filter-section h3 {
    margin-bottom: 20px;
    font-size: 18px;
    font-weight: 600;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 500;
    color: #555;
}

.form-input,
.form-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-input:focus,
.form-select:focus {
    outline: none;
    border-color: #007bff;
}

/* Buttons */
.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
    text-decoration: none;
    display: inline-block;
    text-align: center;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    width: 100%;
    margin-bottom: 10px;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #007bff;
    color: white;
    width: 100%;
}

.btn-secondary:hover {
    background-color: #0056b3;
}

.btn-success {
    background-color: #28a745;
    color: white;
    margin: 5px;
}

.btn-success:hover {
    background-color: #218838;
}

.btn-edit {
    background-color: #6c757d;
    color: white;
    padding: 6px 12px;
    font-size: 12px;
}

.btn-edit:hover {
    background-color: #545b62;
}

.btn-delete {
    background-color: transparent;
    border: none;
    font-size: 16px;
    cursor: pointer;
    padding: 4px;
}

/* Main Content */
.main-content {
    flex: 1;
}

.action-buttons {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 20px;
    gap: 5px;
}

/* Product Cards */
.products-container {
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.product-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.product-info {
    display: flex;
    gap: 20px;
    align-items: center;
    flex-wrap: wrap;
}

.product-detail {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 80px;
}

.product-detail .label {
    font-size: 11px;
    opacity: 0.9;
    margin-bottom: 2px;
}

.product-detail .value {
    font-weight: 600;
    font-size: 13px;
}

.product-detail .value.blue {
    background-color: rgba(255, 255, 255, 0.2);
    padding: 2px 8px;
    border-radius: 4px;
}

.product-detail .value.green {
    background-color: #4CAF50;
    padding: 2px 8px;
    border-radius: 4px;
}

.product-actions {
    display: flex;
    align-items: center;
    gap: 15px;
}

.stock-info {
    background-color: rgba(255, 255, 255, 0.9);
    color: #333;
    padding: 8px 12px;
    border-radius: 4px;
    text-align: center;
    min-width: 80px;
}

.stock-number {
    font-weight: bold;
    font-size: 14px;
}

.stock-label,
.stock-product {
    font-size: 11px;
    color: #666;
}

/* Footer */
.footer {
    background-color: #333;
    color: white;
    text-align: center;
    padding: 20px;
    margin-top: auto;
}

.footer p {
    margin-bottom: 5px;
    font-size: 14px;
}

.footer-link {
    color: #007bff;
    text-decoration: none;
}

.footer-link:hover {
    text-decoration: underline;
}

/* Dark Mode */
body.dark-mode {
    background-color: #1a1a1a;
    color: #e0e0e0;
}

body.dark-mode .header {
    background-color: #2d2d2d;
    border-bottom-color: #444;
}

body.dark-mode .nav-item {
    color: #ccc;
}

body.dark-mode .nav-item:hover,
body.dark-mode .nav-item.active {
    background-color: #404040;
    color: #fff;
}

body.dark-mode .sidebar,
body.dark-mode .products-container {
    background-color: #2d2d2d;
    color: #e0e0e0;
}

body.dark-mode .form-input,
body.dark-mode .form-select {
    background-color: #404040;
    border-color: #555;
    color: #e0e0e0;
}

body.dark-mode .user-icon {
    background-color: #555;
}

body.dark-mode .theme-toggle:hover {
    background-color: #404040;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-center {
        display: none;
    }

    .container {
        flex-direction: column;
        padding: 10px;
    }

    .sidebar {
        width: 100%;
        margin-bottom: 20px;
    }

    .action-buttons {
        justify-content: center;
    }

    .btn-success {
        margin: 2px;
        font-size: 12px;
        padding: 6px 12px;
    }

    .product-card {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .product-info {
        justify-content: center;
    }

    .product-actions {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .nav {
        padding: 0 10px;
    }

    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-success {
        margin: 2px 0;
        width: 100%;
    }

    .product-info {
        flex-direction: column;
        gap: 10px;
    }
}

#producto_spam_texto {
    text-align: center;
    width: 100% !important;
    display: block;
    padding-bottom: 1%;
}

.btn-button-stock-actions {
    text-decoration: none;
    color: white;
}

.red {
    background-color: brown;
}

/*//////
    formulario responsive
/////*/
.responsive-form {
    display: flex;
    flex-direction: column;
    width: 100%;
    /* El formulario ocupa todo el ancho de su contenedor */
    max-width: 600px;
    /* Ancho máximo para que no se vea demasiado ancho en pantallas grandes */
    margin: 20px auto;
    padding: 20px;
    box-sizing: border-box;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.form-dark-m {
    background-color: #333;
}

.inputs-dark-m {
    background-color: #444;
    color: #fff;
}

.titles-dar-m {
    color: #fff !important;
}

.responsive-form h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    margin-bottom: 8px;
    font-weight: bold;
    color: #555;
}

.form-group input[type="text"],
.form-group textarea {
    width: 100%;
    /* Ocupa el 100% del ancho del contenedor .form-group */
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
    /* Asegura que el padding no afecte el ancho total */
}

.form-group textarea {
    resize: vertical;
    /* Permite redimensionar el área de texto verticalmente */
    min-height: 100px;
}

/* Estilos para el botón */
.submit-button {
    background-color: #007bff;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    transition: background-color 0.3s ease;
}

.submit-button:hover {
    background-color: #0056b3;
}

/* Media Queries para ajustar en pantallas más pequeñas */
@media (max-width: 480px) {
    .responsive-form {
        padding: 15px;
    }

    .submit-button {
        padding: 10px 15px;
        font-size: 14px;
    }
}
</style>

<body>
    <header class="header">
        <nav class="nav">
            <div class="nav-left">
                <div class="user-icon">👤</div>
            </div>
            <div class="nav-center">
                <?php
                require_once "validacion-usuario.php";
                require_once "div-nav.php"; ?>
            </div>
            <div class="nav-right">
                <button class="theme-toggle" id="themeToggle">🌙</button>
            </div>
        </nav>
    </header>

    <div class="container">
        <aside class="sidebar">
            <div class="filter-section">
                <h3>Filtrar Productos</h3>

                <div class="form-group">
                    <label for="productName">Nombre del Producto</label>
                    <input type="text" id="productName" class="form-input">
                </div>

                <div class="form-group">
                    <label for="department">Departamento</label>
                    <input type="text" id="department" class="form-input">
                </div>

                <div class="form-group">
                    <label for="provider">Proveedor</label>
                    <select id="provider" class="form-select">
                        <!-- <option>Seleccione proveedor</option> -->
                        <?php require_once "select-proveedores.php";
                        ; ?>
                        <?php foreach ($todosProveedores as $selectproveedor) { ?>
                        <option value="<?php echo $selectproveedor["proveedor"]; ?>">
                            <?php echo $selectproveedor["proveedor"]; ?>
                        </option>
                        <?php }
                        $total = 0;
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="barcode">Código de Barra</label>
                    <input type="text" id="barcode" class="form-input">
                </div>

                <button class="btn btn-primary">Filtrar</button>
            </div>
        </aside>


        <main class="main-content">
            <?php require_once "acciones-stock.php" ?>
            <div class="products-container">
                <div id="producto_spam_texto">Sectores</div>
                <form action="crear-sectores-componente.php" method="post" class="responsive-form">
                    <div class="form-group">
                        <label for="nombre-sector" id="titles-sectores">Nombre del Sector:</label>
                        <input type="text" id="nombre-sector" name="nombre_sector" placeholder="Ej: Limpieza" required>
                    </div>
                    <div class="form-group">
                        <label for="nombre-sector" id="titles-sectores">Lugar</label>
                        <input type="number" id="nombre-sector" name="lugar" placeholder="1=Deposito 2=Reparto">
                    </div>
                    <button type="submit" class="submit-button">Guardar</button>
                </form>
            </div>
        </main>
    </div>

    <footer class="footer">
        <p>Copyright © 2024. Todos los derechos reservados</p>
        <p>Diseñado por <a href="#" class="footer-link">Flavio J. Trocello</a></p>
    </footer>

    <script>
    // Theme Toggle Functionality
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;

    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem('theme') || 'light';
    if (currentTheme === 'dark') {
        body.classList.add('dark-mode');
        themeToggle.textContent = '☀️';
    }

    themeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');

        // Update button icon and save preference
        if (body.classList.contains('dark-mode')) {
            themeToggle.textContent = '☀️';
            localStorage.setItem('theme', 'dark');

            //dark mode styles for form
            const fomD = document.querySelector('.responsive-form');
            fomD.classList.toggle('form-dark-m');
            const darkInputs = document.querySelectorAll('#responsive-form input');
            darkInputs.forEach(input => {
                input.classList.toggle('inputs-dark-m');
            });
            const titlesD = document.querySelectorAll('#titles-sectores');
            titlesD.forEach(title => {
                title.classList.toggle('titles-dar-m');
            });


        } else {
            themeToggle.textContent = '🌙';
            localStorage.setItem('theme', 'light');

            //dark mode styles for form
            const fomD = document.querySelector('.responsive-form');
            fomD.classList.toggle('form-dark-m');
            const darkInputs = document.querySelectorAll('#responsive-form input');
            darkInputs.forEach(input => {
                input.classList.toggle('inputs-dark-m');
            });
            const titlesD = document.querySelectorAll('#titles-sectores');
            titlesD.forEach(title => {
                title.classList.toggle('titles-dar-m');
            });

        }
    });

    // Filter functionality
    const filterButton = document.querySelector('.btn-primary');
    const showProvidersButton = document.querySelector('.btn-secondary');

    filterButton.addEventListener('click', () => {
        const productName = document.getElementById('productName').value;
        const department = document.getElementById('department').value;
        const provider = document.getElementById('provider').value;
        const barcode = document.getElementById('barcode').value;

        // Here you would implement the actual filtering logic
        console.log('Filtering with:', {
            productName,
            department,
            provider,
            barcode
        });

        // Show loading state
        filterButton.textContent = 'Filtrando...';
        filterButton.disabled = true;

        // Simulate API call
        setTimeout(() => {
            filterButton.textContent = 'Filtrar';
            filterButton.disabled = false;
            alert('Filtros aplicados correctamente');
        }, 1000);
    });

    showProvidersButton.addEventListener('click', () => {
        // Toggle providers display
        alert('Mostrando lista de proveedores');
    });

    // Action buttons functionality
    const actionButtons = document.querySelectorAll('.action-buttons .btn-success');

    actionButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            const action = e.target.textContent;

            // Add click animation
            e.target.style.transform = 'scale(0.95)';
            setTimeout(() => {
                e.target.style.transform = 'scale(1)';
            }, 150);

            // Handle different actions
            switch (action) {
                case 'Ordenar por stock':
                    handleSortByStock();
                    break;
                case 'Agregar Producto':
                    handleAddProduct();
                    break;
                case 'Editar por Departamento':
                    handleEditByDepartment();
                    break;
                case 'Backup':
                    handleBackup();
                    break;
                case 'Borrar registros anuales':
                    handleDeleteAnnualRecords();
                    break;
                case 'Crear C.Barra':
                    handleCreateBarcode();
                    break;
                case 'Cargar Masivamente':
                    handleMassLoad();
                    break;
                case 'Crear usuario':
                    handleCreateUser();
                    break;
                default:
                    console.log('Acción no implementada:', action);
            }
        });
    });

    // Action handlers
    function handleSortByStock() {
        alert('Ordenando productos por stock...');
    }

    function handleAddProduct() {
        const productName = prompt('Ingrese el nombre del producto:');
        if (productName) {
            alert(`Producto "${productName}" agregado correctamente`);
        }
    }

    function handleEditByDepartment() {
        alert('Abriendo editor por departamento...');
    }

    function handleBackup() {
        if (confirm('¿Está seguro de que desea crear un backup?')) {
            alert('Backup creado exitosamente');
        }
    }

    function handleDeleteAnnualRecords() {
        if (confirm('¿Está seguro de que desea borrar los registros anuales? Esta acción no se puede deshacer.')) {
            alert('Registros anuales eliminados');
        }
    }

    function handleCreateBarcode() {
        alert('Generador de códigos de barra abierto');
    }

    function handleMassLoad() {
        alert('Función de carga masiva iniciada');
    }

    function handleCreateUser() {
        const username = prompt('Ingrese el nombre de usuario:');
        if (username) {
            alert(`Usuario "${username}" creado correctamente`);
        }
    }

    // Product card interactions
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('btn-edit')) {
            handleEditProduct(e.target);
        } else if (e.target.classList.contains('btn-delete')) {
            handleDeleteProduct(e.target);
        }
    });

    function handleEditProduct(button) {
        const productCard = button.closest('.product-card');
        alert('Editando producto...');
    }

    function handleDeleteProduct(button) {
        if (confirm('¿Está seguro de que desea eliminar este producto?')) {
            const productCard = button.closest('.product-card');
            productCard.style.animation = 'fadeOut 0.3s ease-out';
            setTimeout(() => {
                productCard.remove();
            }, 300);
        }
    }

    // Add fade out animation
    const style = document.createElement('style');
    style.textContent = `
    @keyframes fadeOut {
        from { opacity: 1; transform: translateX(0); }
        to { opacity: 0; transform: translateX(-100%); }
    }
`;
    document.head.appendChild(style);

    // Navigation functionality
    // const navItems = document.querySelectorAll('.nav-item');

    // navItems.forEach(item => {
    //     item.addEventListener('click', (e) => {
    //         e.preventDefault();

    //         // Remove active class from all items
    //         navItems.forEach(nav => nav.classList.remove('active'));

    //         // Add active class to clicked item
    //         e.target.classList.add('active');

    //         // Handle navigation
    //         const section = e.target.textContent;
    //         console.log('Navigating to:', section);

    //         // Here you would implement actual navigation logic
    //         // For now, just show an alert
    //         if (section !== 'Stock') {
    //             alert(`Navegando a la sección: ${section}`);
    //         }
    //     });
    // });

    // Form validation
    const inputs = document.querySelectorAll('.form-input, .form-select');

    inputs.forEach(input => {
        input.addEventListener('blur', validateInput);
        input.addEventListener('input', clearValidation);
    });

    function validateInput(e) {
        const input = e.target;
        const value = input.value.trim();

        // Remove existing validation classes
        input.classList.remove('valid', 'invalid');

        // Add validation based on input type
        if (input.type === 'text' && value.length > 0) {
            input.classList.add('valid');
        } else if (input.tagName === 'SELECT' && value !== '') {
            input.classList.add('valid');
        }
    }

    function clearValidation(e) {
        const input = e.target;
        input.classList.remove('valid', 'invalid');
    }

    // Add validation styles
    const validationStyle = document.createElement('style');
    validationStyle.textContent = `
    .form-input.valid,
    .form-select.valid {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    .form-input.invalid,
    .form-select.invalid {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
    }
`;
    document.head.appendChild(validationStyle);

    // Initialize tooltips (simple implementation)
    function initTooltips() {
        const buttons = document.querySelectorAll('.btn');

        buttons.forEach(button => {
            button.addEventListener('mouseenter', showTooltip);
            button.addEventListener('mouseleave', hideTooltip);
        });
    }

    function showTooltip(e) {
        const button = e.target;
        const text = button.textContent;

        // Create tooltip element
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = `Acción: ${text}`;
        tooltip.style.cssText = `
        position: absolute;
        background: #333;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 12px;
        z-index: 1000;
        pointer-events: none;
        white-space: nowrap;
    `;

        document.body.appendChild(tooltip);

        // Position tooltip
        const rect = button.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 5 + 'px';

        button.tooltip = tooltip;
    }

    function hideTooltip(e) {
        const button = e.target;
        if (button.tooltip) {
            button.tooltip.remove();
            button.tooltip = null;
        }
    }

    // Initialize tooltips
    initTooltips();

    // Responsive menu toggle for mobile
    function initMobileMenu() {
        const header = document.querySelector('.header');
        const nav = document.querySelector('.nav');

        // Create mobile menu button
        const mobileMenuBtn = document.createElement('button');
        mobileMenuBtn.className = 'mobile-menu-btn';
        mobileMenuBtn.innerHTML = '☰';
        mobileMenuBtn.style.cssText = `
        display: none;
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
    `;

        nav.appendChild(mobileMenuBtn);

        // Add mobile menu styles
        const mobileStyle = document.createElement('style');
        mobileStyle.textContent = `
        @media (max-width: 768px) {
            .mobile-menu-btn {
                display: block !important;
            }
            
            .nav-center {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: #e0e0e0;
                flex-direction: column;
                padding: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            }
            
            .nav-center.active {
                display: flex !important;
            }
            
            .nav-item {
                padding: 10px;
                border-bottom: 1px solid #ccc;
            }
        }
    `;
        document.head.appendChild(mobileStyle);

        // Toggle mobile menu
        mobileMenuBtn.addEventListener('click', () => {
            const navCenter = document.querySelector('.nav-center');
            navCenter.classList.toggle('active');
        });
    }

    // Initialize mobile menu
    initMobileMenu();

    console.log('Sistema de Inventario inicializado correctamente');
    </script>
</body>

</html>