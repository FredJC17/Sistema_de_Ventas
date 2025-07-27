
# 🛒 Sistema de Ventas

**Trabajo Aplicativo Final** – Sistema web desarrollado para la gestión de ventas, productos, clientes, proveedores y usuarios en una tienda. Permite llevar el control del stock, registrar facturas, y consultar ventas por producto.


![Menu principal sisventas](https://github.com/user-attachments/assets/a886dab2-6765-4fe3-9a23-59c4b80a71bc)


---

## ⚙️ Tecnologías Utilizadas

- PHP (Programación Orientada a Objetos)
- MySQL
- HTML y CSS
- JavaScript
- Servidor local: XAMPP
- Universe.io (Diseño de interfaces y prototipos)


---

## 📁 Estructura del Proyecto

El proyecto está organizado en carpetas que siguen el enfoque de separación lógica de responsabilidades. A continuación se describe cada una de estas:

### 📁 `controlador/`

Contiene todos los controladores del sistema.  
Aquí se gestiona la lógica principal para cada módulo: productos, clientes, proveedores, usuarios, etc.

Dentro tenemos:
- `clienteController.php`
- `productoController.php`
- `usuarioController.php`


### 📁 `includes/`

Carpeta donde se encuentra la configuración global del sistema.

Archivos clave:
- `config.php`: contiene constantes como el nombre del host o credenciales de conexión.
- `db.php`: contiene la clase que gestiona la conexión a la base de datos con PDO.


### 📁 `modelo/`

Contiene las clases que representan entidades o modelos del sistema, en nuestro caso unicamente esta:
- `producto.php`: contiene atributos y funciones para gestionar productos.


### 📁 `vista/`

Es la carpeta que contiene todas las interfaces del sistema (formularios, listados, botones, etc.).  
Está organizada por subcarpetas según el módulo:

- `clientes/`: Vistas para crear, editar y listar clientes.
- `producto/`: Formulario de producto, stock, consulta de ventas.
- `proveedores/`: Mantenimiento de proveedores.
- `usuarios/`: Gestión de usuarios.
- `ventas/`: Registro de ventas, selección de productos.
- `layout/`: Contiene los elementos comunes como cabecera, menú y pie.



### 📄 `index.php`

Punto de entrada principal al sistema. Normalmente redirige al login si no hay sesión iniciada.


### 📄 `login.php` y `logout.php`

- `login.php`: Página de acceso al sistema con usuario y contraseña.
- `logout.php`: Destruye la sesión activa y redirige al login.
---

## 🔍 Descripción de Módulos


### 🛍️ Comprar

Este es el módulo principal de punto de venta. Permite al usuario (vendedor o cliente) seleccionar productos, añadirlos a un **carrito de compras**, y registrar la venta.

Al confirmar la compra:

- Se genera una factura interna.
- El stock de cada producto se **reduce automáticamente**.
- Se muestran alertas visuales si algún producto tiene **stock bajo** o está **agotado**.

Este módulo simula el comportamiento de una tienda real y es el más dinámico del sistema.

**Archivos relacionados:**
- Vista: `vista/producto/store.php`
- Controlador: `controlador/productoController.php`
- Modelo: `modelo/producto.php`
- 
![menucomprar](https://github.com/user-attachments/assets/96b1d283-9987-4d65-9335-6ffd351b9e7e)



### 🧾 Productos

Este módulo permite gestionar todos los productos disponibles para la venta. Cada producto tiene nombre, precio, stock actual, stock mínimo, categoría y proveedor asignado.

Incluye una **alerta visual** en el listado cuando el stock actual cae por debajo del mínimo, para facilitar el control de inventario.

También permite realizar **consultas de ventas por producto**, mostrando cuántas veces se vendió cada producto, el total recaudado, y fechas.

**Archivos relacionados:**
- Vistas:
  - `vista/producto/listado.php`: listado de productos con controles de edición y alerta por stock.
  - `vista/producto/crear.php`: formulario de registro de producto.
  - `vista/producto/update.php`: formulario de edición de producto existente.
  - `vista/producto/consulta_ventas.php`: consulta de ventas por producto.
- Controlador: `controlador/productoController.php`
- Modelo: `modelo/producto.php`

![menuproductos](https://github.com/user-attachments/assets/068f8a06-c598-4989-8eb7-fc0119248c55)


### 👤 Clientes

Módulo para registrar, editar y eliminar clientes. Cada cliente tiene un DNI, nombres, apellidos, teléfono y dirección. Esta información se asocia a cada venta realizada.

**Archivos relacionados:**
- Vistas: `vista/clientes/`
- Controlador: `controlador/clienteController.php`

![menuclientes](https://github.com/user-attachments/assets/fa0ef6a2-ece6-485f-9529-ea56dba59504)


### 🚚 Proveedores

Permite registrar proveedores que suministran los productos. Cada proveedor tiene nombre, dirección, teléfono, y correo electrónico.

**Archivos relacionados:**
- Vistas: `vista/proveedores/`
- Controlador: `controlador/proveedorController.php`
- 
![menuproveedores](https://github.com/user-attachments/assets/5814e9b8-f576-4161-b097-f8e0f2f1c941)


### 📊 Ventas

Este módulo permite consultar las ventas que se han realizado en el sistema.

Incluye un reporte por cliente donde se puede ver:

- Cliente que realizo la compra.
- Condicion de la venta
- Importe monetario total generado.
- IGV
- Fecha de cada venta.

Sirve como herramienta de análisis o seguimiento de las operaciones realizadas.

**Archivos relacionados:**
- Vista: `vista/producto/consulta_ventas.php`
- Modelo: `modelo/producto.php`

![menuventas](https://github.com/user-attachments/assets/44d84728-f235-4be1-ad3b-0f1082959fe0)


### 👥 Usuarios

Permite crear, editar y eliminar cuentas de usuarios del sistema. Cada usuario tiene un código único, contraseña y nombre.

Incluye control de acceso: solo los usuarios registrados pueden ingresar al sistema.

**Archivos relacionados:**
- Vistas: `vista/usuarios/`
- Controlador: `controlador/usuarioController.php`
- Login:
  - `login.php`: formulario de acceso.
  - `logout.php`: cierre de sesión.
  - `controlador/loginController.php`: verifica las credenciales.
  - 
![menuusuarios](https://github.com/user-attachments/assets/2b267850-7383-46b7-8f0f-9aae6fa6b7a1)


### 🧩 Layout

Contiene los archivos comunes a todas las páginas del sistema: menú de navegación, encabezado, pie de página, y estilos generales.

**Archivos relacionados:**
- Carpeta: `vista/layout/`
  - `cabecera.php`
  - `menu.php`
  - `pie.php`

---
## 🗃️ Base de Datos

El sistema utiliza una base de datos relacional en MySQL llamada `sistemaventas`.  
Contiene las siguientes tablas principales:

- `usuarios`: Manejo de acceso al sistema.
- `clientes`: Datos de los compradores.
- `proveedores`: Información de empresas que abastecen los productos.
- `productos`: Catálogo de productos disponibles, con control de stock y categorías.
- `categorias`: Clasificación de los productos.
- `facturas`: Registro de las ventas realizadas.
- `detallefactura`: Detalle de cada producto vendido.
- `condicionventa`: Define si la venta fue al contado o al crédito.

📎 Archivo SQL incluido: `sistema_ventas.sql`  
Este archivo permite importar toda la estructura y parte de los datos necesarios para probar el sistema.

---

## ▶️ Cómo Ejecutar el Sistema Localmente

Para probar este sistema en tu computadora, sigue estos pasos:


### ⚙️ 1. Iniciar XAMPP

Abre **XAMPP** y activa los siguientes módulos:

- 🟢 Apache  
- 🟢 MySQL


### 📁 2. Colocar el proyecto en la carpeta correcta

Copia la carpeta del proyecto en la siguiente ruta:
C:\xampp\htdocs\sisventas


### 🛠️ 3. Crear la base de datos

Abre tu navegador y accede a:
http://localhost/phpmyadmin


Luego:

1. Crea una base de datos nueva con el nombre: 'sistemaventas'
2. Haz clic en la pestaña **"Importar"**.
3. Selecciona el archivo: sistema_ventas.sql
4. Haz clic en **"Continuar"** para importar los datos correctamente.


### 🌐 4. Ingresar al sistema web

Abre tu navegador y escribe: http://localhost/sisventas/


### 🔐 5. Acceso con usuario de prueba

Inicia sesión usando las siguientes credenciales:

- 👤 **Usuario:** A01  
- 🔑 **Contraseña:** 1234

✅ ¡Listo! Ya puedes usar el sistema: registrar productos, realizar ventas, ver reportes, manejar usuarios y todas las demás opciones que ofrece nuestro sistema.

---
## 📸 Capturas del sistema/pruebas

A continuación se muestran algunas vistas del sistema en funcionamiento:

### 🔐 Inicio de sesion

Interfaz para ingresar al sistema con un usuario registrado.

![menuiniciosesion](https://github.com/user-attachments/assets/b8488155-93fd-4e68-8fc5-d312e3252999)


### 🧾 Menú Principal

Vista principal del sistema luego de iniciar sesión. Permite acceder a todos los módulos.

![menuprincipal](https://github.com/user-attachments/assets/46fb9671-2ec6-4e0e-8e29-61d8ac3f94a7)


### 🛒 Módulo Comprar

Simula la experiencia de compra: muestra los productos y sus datos (ID,precio,stock,etc.), muestra mensajes en caso de poco o nulo stock, permite agregarlos al carrito y finalizar la venta.

![menucomprasf](https://github.com/user-attachments/assets/1fa267cc-09a3-4d0e-a5de-d3c547e40171)

Vista para agregar un producto al carrito:

![agregarcarrito](https://github.com/user-attachments/assets/b69a2b0c-462a-4599-9c90-1ac1f6460ce4)

Vista del carrito:

![carrito](https://github.com/user-attachments/assets/e8d66dc1-43a6-4af4-9e87-e732d2b4d93b)

Vistas de la factura:

![factura1](https://github.com/user-attachments/assets/d09ee66f-6e95-4811-9784-9eb71baa4caf)

![factura2](https://github.com/user-attachments/assets/029ceb3d-3fc3-4e87-83db-e1ec0ef5f1e3)

Vista de la confirmacion de pago:

![confirmacionpago](https://github.com/user-attachments/assets/f0d2b92d-ec49-43cc-b633-06077ab45460)

Al momento de realizar un pago en el menu nos aparecera un mensaje de que nuestra compra se realizo con exito:

![mensajecompraexitosa](https://github.com/user-attachments/assets/be4b94a6-e502-4e54-b471-bdb8c3206e8d)

Boton "BUSCAR": Nos permite realizar busquedas de productos ya sea por:
--> ID
--> Nombre
--> Categoria

![probuscarid](https://github.com/user-attachments/assets/da062a6b-c42f-46ba-9e84-f7270963f7a7)

Con una busqueda existosa nos enviara el siguiente mensaje:

![busquedaexitosapro](https://github.com/user-attachments/assets/46f3499f-70e5-4a78-955f-ab03b9210db1)


### 👥 Módulo de Clientes

Permite registrar, listar, editar y eliminar clientes.

![menuclientes](https://github.com/user-attachments/assets/89cc940b-f1cd-4c76-aab8-c3db2725ba69)

Vista a agregar cliente:

![agregarcliente](https://github.com/user-attachments/assets/789a7502-fd1e-4959-adf6-aba8603a96d7)

Vista a editar cliente:

![editcliente](https://github.com/user-attachments/assets/8a6f2de5-652a-4632-82da-cd7057828628)

Mensaje de confirmacion para eliminar cliente:

![eliminarclie](https://github.com/user-attachments/assets/8c88b467-3f5e-422f-8c82-76923471ea31)

Boton "BUSCAR": Nos permite realizar busquedas de clientes ya sea por:
--> ID
--> Nombre

![buscarcliente](https://github.com/user-attachments/assets/d5ebdefd-b83f-4cc9-93d0-387a06993b4c)

Mensaje despues de una busqueda exitosa de un cliente:

![busquedaclieexitosa](https://github.com/user-attachments/assets/04eb2b8f-1f18-4e7c-9397-5d7d745a3911)


### 📦 Módulo de Productos

Gestión completa del catálogo de productos (nombre, stock, precio, categoría, etc.)

![menuproductos](https://github.com/user-attachments/assets/27ff7e2a-fbc2-4151-b57a-b1c512e89a13)

Vista a agregar producto:

![agregarproducto](https://github.com/user-attachments/assets/3f245250-ba33-4033-9a9e-c9823feed520)

Vista a editar producto:

![editarproducto](https://github.com/user-attachments/assets/8184c476-d0cf-4ea4-9a06-4dd7f4f44ea9)

Mensaje de confirmacion para eliminar un producto:

![eliminarproducto](https://github.com/user-attachments/assets/cae74a52-96d8-4dae-9954-fe1c987dd27f)

Boton "BUSCAR": Nos permite realizar busquedas de productos ya sea por: 
--> ID
--> Nombre
--> Categoria

![probuscarid](https://github.com/user-attachments/assets/da062a6b-c42f-46ba-9e84-f7270963f7a7)

Con una busqueda existosa nos enviara el siguiente mensaje:

![busquedaexitosapro](https://github.com/user-attachments/assets/46f3499f-70e5-4a78-955f-ab03b9210db1)

Boton "REPORTE": Nos permite ver las ganancias por cada producto vendido, podemos filtrar estas por dia, mes o poner nuestro propio lapso de tiempo personalizado:

![reporteganancias](https://github.com/user-attachments/assets/e771dbb3-60d0-438f-9a5f-78f26ec4e751)


### 🚚 Módulo de Proveedores

Módulo para registrar y administrar proveedores.

![menuproveedores](https://github.com/user-attachments/assets/c2e51e79-40d5-4e56-9480-5929b472c72a)

Vista a agregar proveedores:

![agregarprove](https://github.com/user-attachments/assets/7119c784-8bbd-43c1-aa7b-7d66404c5e5a)

Vista a editar proveedores:

![editarprove](https://github.com/user-attachments/assets/5bcb4674-27a9-4345-aadc-06b1dc9a04fc)

Mensaje de confirmacion para eliminar un proveedor:

![eliminarprove](https://github.com/user-attachments/assets/7ee694b6-4802-48ae-9576-71bef43edebc)

Boton "BUSCAR": Nos permite realizar busquedas de proveedores ya sea por: 
--> ID
--> Nombre

![buscarprove](https://github.com/user-attachments/assets/12cd85e7-4751-4db5-99f8-2c43b1f8a5a3)

Con una busqueda existosa nos enviara el siguiente mensaje:

![busquedaproveexitosa](https://github.com/user-attachments/assets/edeb2185-67e3-4570-ac02-ba46612ad396)

### 🧑‍💼 Módulo de Usuarios

Gestión de usuarios del sistema.

![menuusuarios](https://github.com/user-attachments/assets/5982d796-f489-4b8b-a41e-ae99bd161170)

Vista a agregar usuarios:

![agregarusuario](https://github.com/user-attachments/assets/b400fb62-e2df-4bc8-b3d6-70c19d65fe6c)

Vista a editar usuarios (la ID no es editable):

![editusuario](https://github.com/user-attachments/assets/1e1f9516-0619-45ef-8191-2d62f1c15377)

Mensaje de confirmacion para eliminar un usuario:

![elimusuario](https://github.com/user-attachments/assets/2f4360e9-5602-419b-992a-c70f22c7c7cb)

Boton "BUSCAR": Nos permite realizar busquedas de usuarios ya sea por: 
--> ID
--> Nombre

![buscarusuario](https://github.com/user-attachments/assets/595f061b-0624-4deb-8082-eb8ff15f188a)

Con una busqueda existosa nos enviara el siguiente mensaje:

![busquedausuexitosa](https://github.com/user-attachments/assets/e5dfbd48-3253-4ca6-99f3-6224f8577af0)


### 📈 Modulo de Ventas

Muestra un listado de todas las facturas emitadas por el sistema, en cada una se puede ver los detalles de la venta.

![ventas](https://github.com/user-attachments/assets/2ab06201-e601-4eaf-bbf8-476c4fa00e60)

Boton "BUSCAR": Nos permite realizar busquedas de facturas ya sea por: 
--> ID Factura
--> Cliente
--> Condicion

![buscarfactura](https://github.com/user-attachments/assets/a2bc2ca4-b70d-49c0-b12a-1954f5fdf795)

Con una busqueda existosa nos enviara el siguiente mensaje:

![busquedafacturaexitosa](https://github.com/user-attachments/assets/782a310a-42c9-4426-8d8a-9e8c55d3bdcf)

Boton "REPORTE" nos dejara filtrar las facturas por mes, día o un lapso de tiempo de nuestra preferencia. Se nos mostrara la fecha, el total de facturas emitidas y el monto total vendido


![reportefacturas](https://github.com/user-attachments/assets/c10b428a-0b4e-4d5d-8860-184aec0480a5)

---
## 👥 Integrantes del Proyecto

| Nº |           Nombres y Apellidos           | Rol en el equipo |
|----|-----------------------------------------|------------------|
| 1  | JHOSIMAR FRED PANTA CUADROS             | Jefe de Proyecto |
| 2  | JAIR CARPIO LEON                        | Programador      |
| 3  | ELIZABETH PACCO LAURA                   | Programadora     |
| 4  | FABRICIO ROBERTH MOLLEHUANCA BUSTAMANTE | Documentador     |





