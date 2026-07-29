<?php

// Legal pages (privacy + terms), rendered by legal/show.
//
// NOT reviewed by a lawyer. Written to describe accurately what this codebase
// actually does — which is the part an agent can get right — so that a review,
// if the owner wants one, starts from something true rather than from a
// template full of clauses about data the app never collects.
//
// Spanish is the source: en/pt are translations of this file.
return [
    'updated' => 'Última actualización: 28 de julio de 2026',

    // Rendered only when NEXO_LEGAL_OPERATOR / NEXO_LEGAL_CONTACT are set.
    'operator' => [
        'h' => 'Quién opera esta instancia',
        'p' => 'Esta instancia la opera :operator.',
        'contact' => 'Para cualquier consulta sobre tus datos podés escribir a :contact.',
    ],

    'privacy' => [
        'title' => 'Privacidad',
        'intro' => 'Esta instancia de Nexo Links es open source y self-hosted. Recogemos lo mínimo para que tu página de links funcione, y nada más. No hay cookies de seguimiento, ni analítica de terceros, ni pedidos a servidores ajenos desde ninguna página.',
        'sections' => [
            [
                'h' => 'Qué guardamos de tu cuenta',
                'p' => 'Tu nombre, tu email y una versión cifrada (hash) de la contraseña, más la fecha en que verificaste el email. El email se usa para verificar la cuenta, recuperar el acceso y nada más: no mandamos newsletters. Si esta instancia tiene habilitado el inicio de sesión con Nexo ID y lo usás, guardamos además el identificador que ese servicio nos da para reconocerte.',
            ],
            [
                'h' => 'Tu página pública es pública',
                'p' => 'Tu nombre de usuario, tu bio, tu foto, tu portada, tus links (título y destino) y tus iconos sociales se publican en la dirección /tuusuario de este sitio para cualquiera que la tenga, y la página figura en el sitemap para que los buscadores la indexen. La foto y la portada quedan en una carpeta pública del servidor: quien tenga su URL la ve, aunque después borres el link. No pongas ahí nada que no quieras publicar — un teléfono o un email como icono social también quedan a la vista.',
            ],
            [
                'h' => 'Qué medimos de las visitas',
                'p' => 'Solo los clics en tus links. De cada clic guardamos qué link fue, cuándo, una huella anónima del visitante y, si vino de otro sitio, únicamente el dominio de procedencia (por ejemplo "instagram.com", sin la ruta ni los parámetros). Los clics que salen de tu propia página se guardan como directos. No guardamos la IP, ni el navegador, ni la ubicación, ni ningún identificador que persista.',
            ],
            [
                'h' => 'Por qué la huella no puede seguirte',
                'p' => 'La huella es el resultado de aplicar SHA-256 a cuatro cosas juntas: la clave secreta de esta instalación, la fecha de hoy, tu IP y tu navegador. Lo único que se guarda es ese resultado; la IP y el navegador se descartan en el acto y no se pueden recuperar de él. Como la fecha entra en el cálculo, mañana la misma persona produce una huella completamente distinta y no existe forma de emparejar las dos: sirve para contar "cuántas personas distintas hicieron clic hoy" y para nada más. Y como la clave es propia de esta instalación, tampoco se puede cruzar con la de otro sitio.',
            ],
            [
                'h' => 'Cookies',
                'p' => 'Solo las necesarias para que la web funcione: la de sesión y la de protección de formularios (que se emiten cuando entrás a tu cuenta), y dos que recuerdan tu preferencia de idioma y de tema claro/oscuro. Estas dos últimas viajan sin cifrar y con alcance al dominio padre a propósito, para que tu elección se respete en todas las herramientas Nexo; no llevan datos personales. Ninguna sirve para publicidad ni para seguimiento, y por eso no verás un banner de consentimiento.',
            ],
            [
                'h' => 'Mientras tenés la sesión abierta',
                'p' => 'El registro de sesión del servidor guarda tu IP y tu navegador mientras la sesión está viva, para poder cerrarla y detectar abusos. Se borra al cerrar sesión o al expirar. Esto pasa solo si tenés cuenta e iniciaste sesión: visitar una página pública no crea ninguna sesión.',
            ],
            [
                'h' => 'Reportes',
                'p' => 'Cualquiera puede reportar una página o un link sin registrarse. De un reporte guardamos el motivo, el comentario opcional que escribas, a qué página o link apunta y la misma huella diaria anónima, que solo se usa para no aceptar el mismo reporte dos veces en un día. No pedimos email ni nombre a quien reporta. El dueño de la página ve el motivo y el comentario.',
            ],
            [
                'h' => 'Correos',
                'p' => 'Los únicos correos que enviamos son los de la cuenta: verificación del email y recuperación de contraseña. Salen por un proveedor de email externo, que necesariamente procesa la dirección de destino y el contenido para poder entregarlos.',
            ],
            [
                'h' => 'Nada externo en las páginas',
                'p' => 'Ninguna página carga fuentes, scripts, imágenes ni CDNs de terceros: todo se sirve desde este dominio y la política de seguridad de contenido del sitio lo bloquea explícitamente. Eso significa que visitar tu página no le avisa a nadie más de que la visitaste. Existe un contador opcional del ecosistema Nexo, apagado por defecto, que solo envía la señal de "una visita a esta herramienta", sin identificar a nadie ni a qué página fue.',
            ],
            [
                'h' => 'Cuánto tiempo y cómo se borra',
                'p' => 'Los datos viven mientras tengas la cuenta. Al borrar la cuenta se borran en cascada tu página, tus links, tus iconos sociales, los clics registrados y los reportes recibidos. Al cambiar o quitar tu foto o tu portada, el archivo anterior se elimina del servidor.',
            ],
            [
                'h' => 'Tus derechos',
                'p' => 'Podés ver y editar tus datos desde tu propio panel, y borrarlos por completo borrando la cuenta. Para pedir acceso, corrección o borrado por otra vía, escribí a quien opera esta instancia con el contacto que aparece al pie de esta página.',
            ],
            [
                'h' => 'Otras instancias',
                'p' => 'Nexo Links se puede instalar en cualquier servidor. Cada instalación es independiente, tiene su propia clave y es responsable de sus propios datos: esta política habla solo de esta instancia.',
            ],
        ],
    ],

    'terms' => [
        'title' => 'Términos de uso',
        'intro' => 'Al usar esta instancia de Nexo Links aceptás lo que sigue. Es un servicio gratuito, ofrecido tal cual está.',
        'sections' => [
            [
                'h' => 'Qué es el servicio',
                'p' => 'Una herramienta para publicar una página con todos tus links en una dirección propia del tipo /tuusuario, con estadísticas de clics, programación de links por fecha y personalización visual. No alojamos tu contenido: alojamos los enlaces que apuntan a él.',
            ],
            [
                'h' => 'Tu cuenta y tu nombre de usuario',
                'p' => 'Necesitás una cuenta y verificar tu email para publicar. Sos responsable de mantener tu contraseña a salvo y de lo que se haga desde tu cuenta. Hay nombres de usuario que no se pueden registrar: los que chocan con las direcciones de la propia aplicación y los que servirían para hacerse pasar por el sitio. Quien opera esta instancia puede recuperar un nombre de usuario que se esté usando para suplantar a una persona o marca.',
            ],
            [
                'h' => 'Responsabilidad sobre tus links',
                'p' => 'El contenido al que apuntan tus links es responsabilidad tuya, no nuestra. Por seguridad de quien hace clic, solo se aceptan direcciones http, https, mailto y tel: cualquier otro esquema (javascript:, data:, file: y similares) se rechaza al guardar, porque son los que se usan para ejecutar código en el navegador de la visita.',
            ],
            [
                'h' => 'Uso indebido',
                'p' => 'No se permite usar una página para phishing, malware, estafas, suplantación de identidad, spam ni contenido ilegal. Cualquier visitante puede reportar una página o un link concreto desde el enlace de reporte de esa página, y quien opera esta instancia puede dar de baja la página: deja de estar disponible y sus links dejan de redirigir.',
            ],
            [
                'h' => 'Disponibilidad',
                'p' => 'El servicio se ofrece sin garantías de disponibilidad. Hacemos lo razonable para que esté en línea, pero puede haber interrupciones, y una dirección que compartiste puede quedar sin responder durante ellas.',
            ],
            [
                'h' => 'Límite de responsabilidad',
                'p' => 'Quien opera esta instancia no se hace responsable de daños derivados del uso del servicio, incluidos links que dejen de funcionar, estadísticas que se pierdan o interrupciones del servicio.',
            ],
            [
                'h' => 'Software libre',
                'p' => 'Nexo Links se distribuye con licencia MIT: podés leer el código, modificarlo y alojar tu propia instancia. El software se entrega sin garantías, según indica esa licencia.',
            ],
            [
                'h' => 'Cambios',
                'p' => 'Estos términos pueden cambiar. La fecha de arriba indica la última actualización.',
            ],
        ],
    ],
];
