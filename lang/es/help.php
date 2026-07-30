<?php

// Preguntas frecuentes del centro de ayuda de Nexo Links (las renderiza
// HelpController). Las respuestas pueden contener HTML: se imprimen con {!! !!}.
return [
    'faqs' => [
        [
            'q' => '¿Cómo creo mi página?',
            'a' => 'Haz clic en "Crea tu página", elige un nombre de usuario —será tu URL pública en /tu-usuario— y verifica tu correo con el enlace que te enviamos. Tu página queda en línea al instante.',
        ],
        [
            'q' => '¿Cómo agrego y reordeno mis links?',
            'a' => 'En el panel, tocá "+ Agregar link" y ponele un título y una URL. Arrastrá el tirador a la izquierda de cada tarjeta para reordenar, y usá Ocultar para conservar un link sin mostrarlo o Editar para cambiarlo cuando quieras.',
        ],
        [
            'q' => '¿Puedo programar links, destacarlos o poner una cuenta regresiva?',
            'a' => 'Sí. Al crear o editar un link puedes poner fechas de inicio y fin opcionales para que se publique y despublique solo, marcar "Destacar este link" para el tratamiento con el color de acento, o activar una cuenta regresiva para que las visitas vean un temporizador hasta que arranque.',
        ],
        [
            'q' => '¿Un link puede enviar un correo, llamarme o abrir WhatsApp?',
            'a' => 'Los links aceptan https://, mailto: y tel:, así que un botón puede escribirte un correo o llamarte. Usá "Armar un link de WhatsApp" para generar un enlace wa.me con un mensaje ya escrito.',
        ],
        [
            'q' => '¿Cómo funcionan los iconos sociales?',
            'a' => 'En "Iconos sociales", elige una plataforma e ingresa tu usuario, correo o teléfono. Aparecen como iconos al pie de tu página. ¿Prefieres un botón grande? Agrégalo como un link normal.',
        ],
        [
            'q' => '¿Cómo personalizo el aspecto de mi página?',
            'a' => 'En Diseño, sube un avatar y un banner, escribe tu bio y elige una paleta de acento y un fondo: predeterminado, color sólido o degradado. El color del texto se adapta automáticamente para que tu página siga siendo legible.',
        ],
        [
            'q' => '¿Qué muestran las estadísticas y rastrean a las visitas?',
            'a' => 'Las estadísticas muestran clics totales, visitantes únicos, clics por día y tus principales referentes. Los números se recogen sin cookies y sin guardar datos personales: las visitas nunca se rastrean de un día a otro.',
        ],
        [
            'q' => '¿Cómo comparto mi página?',
            'a' => 'Copia tu URL desde "Comparte tu página" en el panel, o descarga tu código QR en SVG e imprímelo donde quieras: siempre apunta a tu página.',
        ],
    ],
];
