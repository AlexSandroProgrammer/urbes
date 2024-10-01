    <!-- / Content -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <!--/ Basic footer -->
        <hr class="container-m-nx border-light my-5" />
        <!-- Footer with components -->
        <section id="component-footer">
            <footer class="footer bg-light">
                <div
                    class="container-fluid d-flex flex-lg-row flex-column justify-content-between align-employees-md-center gap-1 container-p-x py-3">
                    <div class="mb-2 mb-md-0">
                        ©
                        <script>
                        document.write(new Date().getFullYear());
                        </script>
                        , Todos los derechos reservados, diseñado y desarrollado por
                        <a href="#" class="footer-link fw-bolder">URBES</a>
                    </div>
                    <div>
                        <a href="index.php?logout" class="btn btn-sm btn-outline-danger"><i
                                class="bx bx-log-out-circle"></i>Cerrar Sesion</a>
                    </div>
                </div>
            </footer>
        </section>
        <!--/ Footer with components -->


        <div class="content-backdrop fade"></div>
    </div>
    <!-- Content wrapper -->
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->
    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="../../libraries/jquery/jquery-3.3.1.min.js"></script>
    <!-- datatables JS -->
    <script type="text/javascript" src="../../libraries/datatables/datatables.min.js"></script>
    <!-- para usar botones en datatables JS -->
    <script src="../../libraries/datatables/Buttons-1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="../../libraries/datatables/JSZip-2.5.0/jszip.min.js"></script>
    <script src="../../libraries/datatables/pdfmake-0.1.36/pdfmake.min.js"></script>
    <script src="../../libraries/datatables/pdfmake-0.1.36/vfs_fonts.js"></script>
    <script src="../../libraries/datatables/Buttons-1.5.6/js/buttons.html5.min.js"></script>
    <!-- código JS propìo-->
    <!-- Vendors JS -->
    <script src="../../assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <!-- Main JS -->
    <!-- Page JS -->
    <script src="../../assets/js/dashboards-analytics.js"></script>
    <script src="../../js/functions.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script type="text/javascript" src="../../js/props-datatable.js"></script>
    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script>
// FUNCION PARA REGISTRAR DATOS
function transferirDatos(event) {
    event.preventDefault();
    const employees = JSON.parse(localStorage.getItem('empleados'));
    console.log(empleados);
    if (!employees || employees.length < 1) {
        swal.fire({
            title: 'Error',
            text: 'Debes seleccionar al menos un empleado de cualquier ciudad',
            icon: 'error',
            confirmButtonText: 'Aceptar'
        });
        return;
    }

}
document.addEventListener('DOMContentLoaded', function() {
    const ciudad = document.getElementById('ciudad');
    const empleados = document.getElementById('empleados');
    const empleadoList = document.getElementById('empleado-list');

    // Función para guardar empleados en el localStorage
    function saveEmpleadoToLocalStorage(empleados) {
        localStorage.setItem('empleados', JSON.stringify(empleados));
    }

    // Función para obtener los empleados desde el localStorage
    function getEmpleadosFromLocalStorage() {
        return JSON.parse(localStorage.getItem('empleados')) || [];
    }


    function deleteEmpleados() {
        localStorage.removeItem('empleados');
    }

    // Función para manejar la selección y deselección de empleados
    function handleCheckboxChange(event) {
        const checkbox = event.target;
        const empleadoId = checkbox.getAttribute('data-empleado-id');
        const empleadoNombre = checkbox.getAttribute('data-empleado-nombre');
        let empleadosSeleccionados = getEmpleadosFromLocalStorage();

        if (checkbox.checked) {
            // Agregar empleado si se selecciona
            empleadosSeleccionados.push({
                id: empleadoId,
                nombre: empleadoNombre
            });
        } else {
            // Eliminar empleado si se deselecciona
            empleadosSeleccionados = empleadosSeleccionados.filter(
                empleado => empleado.id !== empleadoId
            );
        }
        // Guardar el nuevo arreglo de empleados seleccionados en localStorage
        saveEmpleadoToLocalStorage(empleadosSeleccionados);
    }
    // Función para cargar los checkboxes seleccionados al recargar la página
    function loadSelectedCheckboxes() {
        const empleadosSeleccionados = getEmpleadosFromLocalStorage();
        empleadosSeleccionados.forEach(empleado => {
            const checkbox = document.querySelector(
                `input[data-empleado-id="${empleado.id}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });
    }
    ciudad.addEventListener('change', function() {
        const selectedValue = this.value;
        // Ocultamos la lista de empleados si no se selecciona una ciudad
        if (selectedValue === '') {
            empleados.style.display = 'none';
            empleadoList.innerHTML =
                ''; // Limpiamos el contenido previo
            return;
        }
        // Realizamos la solicitud AJAX
        fetch(`get_empleados_ciudad.php?id_ciudad=${selectedValue}`)
            .then(response => response.json())
            .then(data => {
                // Limpiamos las opciones previas de empleados
                empleadoList.innerHTML = '';
                if (data.error) {
                    console.error(data.error);
                    empleados.style.display = 'none';
                    return;
                }

                // Generamos los checkboxes para cada empleado
                data.forEach(empleado => {
                    const empleadoDiv = document
                        .createElement('div');
                    empleadoDiv.className =
                        'col-lg-3 col-md-4 col-sm-6 col-12 mb-3';

                    empleadoDiv.innerHTML = `
                                                        <div class="d-flex align-employees-center">
                                                            <h6 class="mb-0 me-2">${empleado.nombres} ${empleado.apellidos}</h6>
                                                            <div class="form-check form-switch ms-auto">
                                                                <input class="form-check-input empleado-checkbox" type="checkbox" data-empleado-id="${empleado.documento}" data-empleado-nombre="${empleado.nombres} ${empleado.apellidos}" />
                                                            </div>
                                                        </div>`;

                    empleadoList.appendChild(empleadoDiv);
                });

                // Asignar evento a los checkboxes para manejar la selección/deselección
                document.querySelectorAll('.empleado-checkbox')
                    .forEach(checkbox => {
                        checkbox.addEventListener('change',
                            handleCheckboxChange);
                    });

                // Cargar los checkboxes seleccionados previamente
                loadSelectedCheckboxes();

                // Mostramos el select de empleados
                empleados.style.display = 'block';
            })
            .catch(error => {
                console.error('Error al obtener los empleados',
                    error);
            });
    });

    // Cargar los empleados seleccionados desde el localStorage al iniciar la página
    loadSelectedCheckboxes();
});
    </script>
    <script>
function disableSubmitButton(form) {
    const submitButton = form.querySelector('input[type="submit"]');
    submitButton.disabled = true; // Deshabilita el botón
    submitButton.value = "Enviando..."; // Cambia el texto del botón si lo deseas
}
    </script>
    </body>

    </html>