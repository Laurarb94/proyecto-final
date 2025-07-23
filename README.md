💼 MI EMPLEO - PLATAFORMA DE BÚSQUEDA DE EMPLEO
---------------------------------------------------------

Mi Empleo es una aplicación web orientada a personas de entre 30 y 40 años que desean reinventarse profesionalmente. La 
plataforma permite buscar ofertas de empleo, inscribirse en cursos relacionados y comunicarse con otros usuarios, 
todo desde una misma interfaz accesible y escalable. 

---------------------------------------------------------
🛠 Tecnologías utilizadas

Backend: Symfony (API REST)

Frontend: Vue.js

Base de datos: MySQL (gestionada con phpMyAdmin)

-----------------------------------------------------


✨ Funcionalidades principales

🔍 Buscar ofertas y cursos relacionados. Al buscar una oferta de empleo, se muestra un curso recomendado relacionado con la oferta. Posibilidad de apuntarse y desapuntarse tanto a ofertas como a cursos.

👤 Gestión de perfil. Los usuarios pueden editar su perfil. Al pasar el cursor sobre los datos del perfil, estos se modifican dinámicamente para facilitar la edición.

💬 Sistema de mensajería. Permite mantener conversaciones con otros usuarios. Las conversaciones se actualizan mediante recarga manual de la página (no en tiempo real por websockets).

🛡 Roles y administración. Existen distintos roles de usuario:

Usuario normal: puede acceder a su perfil, buscar empleo y cursos, y chatear con otros usuarios.

Administrador: además de las funciones anteriores, accede a un panel de administración desde donde puede ver la lista de todos los usuarios, editar o eliminar a los usuarios.

--------------------------------------------------------------------------
📸 Capturas de pantalla. A continuación, se muestran algunas imágenes representativas de la plataforma:

1. Vista principal de la página, donde el usuario podrá loguearse o registrarse. 

<img width="2795" height="1344" alt="1" src="https://github.com/user-attachments/assets/afbd014d-e186-4a5b-9029-3313092b82b9" />



2. Vista para poder hacer login

<img width="2800" height="1341" alt="2" src="https://github.com/user-attachments/assets/a8c54e98-0111-44bd-b3d2-7dd48bdd2be9" />



3. Vista principal de la página privada del usuario, con las distintas opciones que puede realizar.

<img width="2814" height="1337" alt="3" src="https://github.com/user-attachments/assets/2079c93d-7b3b-41c5-ba98-6165941229a8" />



4. Página de perfil del usuario, con sus datos y su CV. Si el usuario pasa el ratón por encima de sus datos podrá editarlos.

<img width="2388" height="1335" alt="4" src="https://github.com/user-attachments/assets/ce6d52ab-607f-479b-a7d5-ac2d91dc6fcd" />



5. Opción para buscar la categoría y subcategoría de la oferta laboral que quiere buscar el usuario.

<img width="2757" height="1332" alt="5" src="https://github.com/user-attachments/assets/22a1e920-5152-4874-84cc-aba5493ba317" />



6. Resultados de búsqueda con ofertas laborales y cursos relacionados.

<img width="2784" height="1346" alt="6" src="https://github.com/user-attachments/assets/75c389e1-b4f2-4703-b05f-9274a28fce62" />



7. Detalle de oferta de empleo con opción de eliminar. Para ver el detalle de los cursos la vista será igual. 

<img width="2791" height="1302" alt="7" src="https://github.com/user-attachments/assets/4d4a7acf-afed-414b-bdb1-7baa00215da9" />



8. Vista en la que los usuarios podrán chatear con otros usuarios.

<img width="2836" height="1254" alt="8" src="https://github.com/user-attachments/assets/47c4889e-e4c6-42ad-93a2-63f274982d14" />


9. Menú del administrador. Tendrá las mismas opciones que un usuario normal, más su panel de administrador.

<img width="2792" height="1344" alt="9" src="https://github.com/user-attachments/assets/8fe6844a-406a-464e-a226-53763c0d17fe" />


10. Menú del administrador, donde podrá buscar a los usuarios de la app, editar sus datos y/o eliminarlos.

<img width="2813" height="1299" alt="10" src="https://github.com/user-attachments/assets/291fa821-2e00-417a-853e-97f1691589c7" />


---------------------------------------------------------------------------------------------------------------

🚀 Cómo ejecutar el proyecto 

🔧 Backend (Symfony)

git clone https://github.com/Laurarb94/proyecto-final/backend.git

cd backend

composer install 

Configuración del entorno. Antes de continuar, debes crear un archivo .env.local con tus variables de
entorno. Debe incluir las credenciales de la bbdd y las claves y configuración necesarias para la autenticación con JWT. 

php bin/console doctrine:database:create

php bin/console doctrine:migrations:migrate

symfony server:start

-------------------------------------------------------------------------------

🌐 Frontend (Vue)

git clone https://github.com/Laurarb94/front-vue.git

cd front-vue

npm install 

npm run serve

---------------------------------------------------------------------------------

🧑‍🎓 Autora

Laura Rodríguez Basanta

Trabajo Final de Grado - Desarrollo de Aplicaciones Web

Fecha: Junio 2025

© 2025 Laura Rodríguez Basanta – Todos los derechos reservados.



