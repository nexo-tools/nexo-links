// Builds lang/{es,pt} from laravel-lang (framework + Breeze strings) merged with
// Nexo's own translations below. Portuguese uses the canonical ecosystem code
// `pt`, but its content is sourced from laravel-lang's Brazilian `pt_BR` locale.
// Run after adding new __() strings: node scripts/generate-translations.mjs
//
// Usage: node scripts/generate-translations.mjs [--check]
//   --check  writes nothing and exits non-zero if es/pt are out of sync: either a
//            source string has no translation, or a lang file on disk differs
//            from what this script produces (someone hand-edited it, or forgot
//            to re-run). Used by TranslationsSyncTest and by the CI i18n step.
import { existsSync, mkdirSync, readdirSync, readFileSync, statSync, writeFileSync } from 'node:fs';
import { join } from 'node:path';

const CHECK = process.argv.includes('--check');
const SOURCE_ROOTS = ['app', 'resources/views'];

const AUTH_KEYS = ['failed', 'password', 'throttle'];
const PASSWORD_KEYS = ['reset', 'sent', 'throttled', 'token', 'user'];
const PAGINATION_KEYS = ['next', 'previous'];

// Nexo-specific strings (and tone overrides). Keys are the English source.
const nexo = {
    es: {
        'An account with this email already exists. Verify your email on Nexo ID first.':
            'Ya existe una cuenta con este correo. Verifica tu correo en Nexo ID primero.',
        'Continue with Nexo ID': 'Continuar con Nexo ID',
        'Sign-in with Nexo ID failed. Please try again.':
            'Falló el inicio de sesión con Nexo ID. Inténtalo de nuevo.',
        'Sign-in with Nexo ID is temporarily unavailable. Please try again later.':
            'El inicio de sesión con Nexo ID no está disponible por ahora. Inténtalo de nuevo más tarde.',
        'The sign-in request could not be validated. Please try again.':
            'No se pudo validar la solicitud de inicio de sesión. Inténtalo de nuevo.',
        'or': 'o',
        ':count click|:count clicks': ':count clic|:count clics',
        ':days days': ':days días',
        'Accent palette': 'Paleta de acento',
        'Access denied': 'Acceso denegado',
        'Add': 'Agregar',
        'Add link': 'Agregar link',
        "An open-source link-in-bio page you host yourself — with visitor analytics that don't spy on anyone.":
            'Una página link-in-bio de código abierto que alojas tú mismo — con estadísticas de visitas que no espían a nadie.',
        'Analytics': 'Estadísticas',
        'Analytics without spying': 'Estadísticas sin espiar',
        'Are you sure you want to delete your account?': '¿Seguro que quieres borrar tu cuenta?',
        'Avatar': 'Avatar',
        'Avatar, banner, color palettes, solid or gradient backgrounds — with automatic dark mode and readable text guaranteed.':
            'Avatar, banner, paletas de colores, fondos sólidos o degradados — con modo oscuro automático y texto legible garantizado.',
        'Back to home': 'Volver al inicio',
        'Background': 'Fondo',
        'Banner': 'Portada',
        'Bio': 'Bio',
        'Build a WhatsApp link': 'Armar un link de WhatsApp',
        'Click stats with zero cookies and zero personal data stored. No consent banner needed — private by design.':
            'Estadísticas de clics sin cookies y sin guardar datos personales. Sin banner de consentimiento — privado por diseño.',
        'Clicks': 'Clics',
        'Clicks per day': 'Clics por día',
        'Clicks per day, last :days days': 'Clics por día, últimos :days días',
        'Color': 'Color',
        'Copied!': '¡Copiado!',
        'Copy URL': 'Copiar URL',
        'Create your page': 'Crea tu página',
        "Create your page — it's free": 'Crea tu página — es gratis',
        'Create yours': 'Crea la tuya',
        'Dashboard': 'Panel',
        'Default': 'Por defecto',
        'Delete': 'Eliminar',
        'Delete this link?': '¿Eliminar este link?',
        'Remove this social icon?': '¿Quitar este icono social?',
        'Design': 'Diseño',
        'Design updated.': 'Diseño actualizado.',
        'Download QR (SVG)': 'Descargar QR (SVG)',
        'Drag to reorder': 'Arrastrar para reordenar',
        'Edit': 'Editar',
        'Ends at (optional)': 'Termina el (opcional)',
        'Expired': 'Vencido',
        'Fast and lightweight': 'Rápido y liviano',
        'Features': 'Características',
        'For security reasons, reload the page and try again.':
            'Por seguridad, recarga la página e inténtalo de nuevo.',
        'Gradient': 'Degradado',
        'Hidden': 'Oculto',
        'Hide': 'Ocultar',
        'Highlight this link': 'Destacar este link',
        'Highlighted': 'Destacado',
        'Link': 'Link',
        'Link created.': 'Link creado.',
        'Link deleted.': 'Link eliminado.',
        'Link updated.': 'Link actualizado.',
        'Links': 'Links',
        'Links with superpowers': 'Links con superpoderes',
        'Log Out': 'Cerrar sesión',
        'Log in': 'Iniciar sesión',
        'MIT licensed, self-hostable on cheap shared hosting (PHP + MySQL). Read the code, run your own, contribute.':
            'Licencia MIT, autoalojable en hosting compartido económico (PHP + MySQL). Lee el código, corre el tuyo, contribuye.',
        'No external referrers yet — clicks from your page or shared links without a referrer count as direct.':
            'Aún no hay referentes externos — los clics desde tu página o desde links compartidos sin referente cuentan como directos.',
        'No links yet.': 'Aún no hay links.',
        'No links yet. Add your first one!': 'Aún no hay links. ¡Agrega el primero!',
        'No vendor lock-in': 'Sin ataduras a plataformas',
        'Nothing here yet.': 'Todavía no hay nada aquí.',
        'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.':
            'Cuando borres tu cuenta, todos sus recursos y datos se eliminarán de forma permanente. Escribe tu contraseña para confirmar que quieres borrar la cuenta definitivamente.',
        'Open source': 'Código abierto',
        'Open source on GitHub': 'Código abierto en GitHub',
        "Open-source link-in-bio page you host yourself, with visitor analytics that don't spy on anyone.":
            'Página link-in-bio de código abierto que alojas tú mismo, con estadísticas de visitas que no espían a nadie.',
        'Page not found': 'Página no encontrada',
        'Per link': 'Por link',
        'Prefilled message (optional)': 'Mensaje precargado (opcional)',
        'Print it, add it to a business card or a story — it always points to your page.':
            'Imprímelo, ponlo en una tarjeta o en una historia — siempre apunta a tu página.',
        'Privacy': 'Privacidad',
        'QR code that opens your page': 'Código QR que abre tu página',
        'Remove :platform': 'Quitar :platform',
        'Remove avatar': 'Quitar avatar',
        'Remove banner': 'Quitar banner',
        'Save': 'Guardar',
        'Schedule links by date, highlight what\'s live right now, and tease launches with a countdown.':
            'Programa links por fecha, destaca lo que está en vivo ahora y anticipa lanzamientos con una cuenta regresiva.',
        'Scheduled': 'Programado',
        'See a live example': 'Ver un ejemplo en vivo',
        "Server-rendered pages with no trackers and no external requests. Built mobile-first, because that's where your visitors are.":
            'Páginas renderizadas en el servidor, sin rastreadores ni peticiones externas. Diseñado primero para el celular, porque ahí están tus visitas.',
        'Share your page': 'Comparte tu página',
        'Show': 'Mostrar',
        'Show countdown before it starts': 'Mostrar cuenta regresiva antes de que empiece',
        'Shown as icons at the bottom of your page. Prefer a big button? Add it as a regular link instead.':
            'Se muestran como iconos al pie de tu página. ¿Prefieres un botón grande? Agrégalo como link normal.',
        'Social icon added.': 'Icono social agregado.',
        'Social icon removed.': 'Icono social quitado.',
        'Social icons': 'Iconos sociales',
        'Social profiles': 'Perfiles sociales',
        'Solid color': 'Color sólido',
        'Something went wrong': 'Algo salió mal',
        'Starts at (optional)': 'Empieza el (opcional)',
        'Terms': 'Términos',
        'The :attribute ":input" is reserved.': 'El :attribute ":input" está reservado.',
        'The :attribute may only contain lowercase letters, numbers, hyphens and underscores.':
            'El campo :attribute solo puede contener minúsculas, números, guiones y guiones bajos.',
        'The :attribute must be a valid URL.': 'El campo :attribute debe ser una URL válida.',
        'The :attribute must start with http://, https://, mailto: or tel:.':
            'El campo :attribute debe empezar con http://, https://, mailto: o tel:.',
        'The page expired': 'La página expiró',
        'The problem is on our side. Try again in a few minutes.':
            'El problema es nuestro. Inténtalo de nuevo en unos minutos.',
        'This will be your public page URL. Lowercase letters, numbers, hyphens and underscores.':
            'Esta será la URL pública de tu página. Minúsculas, números, guiones y guiones bajos.',
        'Title': 'Título',
        'To': 'Hasta',
        'Too many requests': 'Demasiadas solicitudes',
        'Top referrers': 'Principales referentes',
        'Total clicks': 'Clics totales',
        'URL': 'URL',
        'Under maintenance': 'En mantenimiento',
        'Unique': 'Únicos',
        'Unique visitors': 'Visitantes únicos',
        'Use the international format, e.g. +5491122334455.': 'Usa el formato internacional, p. ej. +5491122334455.',
        'Use this link': 'Usar este link',
        'Use your handle without the @, e.g. yourname.': 'Usa tu usuario sin el @, p. ej. yourname.',
        'Used for your avatar ring and highlighted links.': 'Se usa en el anillo de tu avatar y en los links destacados.',
        'Username': 'Nombre de usuario',
        'View my page': 'Ver mi página',
        'We are making improvements. We will be back in a few minutes.':
            'Estamos haciendo mejoras. Volvemos en unos minutos.',
        'We could not find what you were looking for. The link may have changed or the page may no longer exist.':
            'No encontramos lo que buscabas. Puede que el link haya cambiado o que la página ya no exista.',
        'You already added this platform.': 'Ya agregaste esta plataforma.',
        'You do not have permission to see this page.': 'No tienes permiso para ver esta página.',
        'You went a little too fast. Wait a moment and try again.':
            'Fuiste un poco demasiado rápido. Espera un momento e inténtalo de nuevo.',
        'Your data.': 'Tus datos.',
        'Your domain.': 'Tu dominio.',
        'Your handle, email or URL': 'Tu usuario, email o URL',
        'Your links.': 'Tus links.',
        'Your links. Your domain. Your data.': 'Tus links. Tu dominio. Tus datos.',
        'Your look': 'Tu estilo',
        'Your page lives on your own domain and server. No platform can paywall it, break your URL or shut it down.':
            'Tu página vive en tu propio dominio y servidor. Ninguna plataforma puede cobrarte por ella, romper tu URL ni cerrarla.',
        'starts in': 'empieza en',
        'Language': 'Idioma',
        'Help': 'Ayuda',
        'Help topics': 'Temas de ayuda',
        'What can I do in :app?': '¿Qué puedo hacer en :app?',
        'Create your account': 'Crea tu cuenta',
        'Click "Create your page" and choose a username — it becomes your public URL.':
            'Haz clic en "Crea tu página" y elige un nombre de usuario — será tu URL pública.',
        'Verify your email address with the link we send you.':
            'Verifica tu email con el enlace que te enviamos.',
        'Your page is immediately live at /your-username.':
            'Tu página queda publicada al instante en /tu-usuario.',
        'Add and organize your links': 'Agrega y organiza tus links',
        'In the dashboard, press "+ Add link" and give it a title and a URL.':
            'En el panel, presiona "+ Agregar link" y ponle un título y una URL.',
        'Drag the handle on the left of each card to reorder.':
            'Arrastra el asa a la izquierda de cada tarjeta para reordenar.',
        'Use Hide to keep a link without showing it, and Edit to change it anytime.':
            'Usa Ocultar para conservar un link sin mostrarlo, y Editar para cambiarlo cuando quieras.',
        'Schedule, highlight and countdowns': 'Programación, destacados y cuentas regresivas',
        'When creating or editing a link, set optional start and end dates — it publishes and unpublishes itself.':
            'Al crear o editar un link, define fechas de inicio y fin opcionales — se publica y despublica solo.',
        'Mark "Highlight this link" to give it the accent color treatment.':
            'Marca "Destacar este link" para darle el color de acento.',
        'Enable the countdown to tease a launch: visitors see a live timer until it starts.':
            'Activa la cuenta regresiva para anticipar un lanzamiento: los visitantes ven un contador en vivo hasta que empiece.',
        'Contact buttons and WhatsApp': 'Botones de contacto y WhatsApp',
        'Links accept https://, mailto: and tel: — so a button can send an email or call you.':
            'Los links aceptan https://, mailto: y tel: — así un botón puede enviar un email o llamarte.',
        'Use "Build a WhatsApp link" to generate a wa.me link with a prefilled message.':
            'Usa "Armar un link de WhatsApp" para generar un enlace wa.me con mensaje precargado.',
        'In "Social icons", pick a platform and enter your handle, email or phone.':
            'En "Iconos sociales", elige una plataforma e ingresa tu usuario, email o teléfono.',
        'Make it yours': 'Hazla tuya',
        'In Design, upload an avatar and a banner, and write your bio.':
            'En Diseño, sube un avatar y un banner, y escribe tu bio.',
        'Pick an accent palette and a background: default, solid color or gradient.':
            'Elige una paleta de acento y un fondo: por defecto, color sólido o degradado.',
        'Text color adapts automatically so your page stays readable.':
            'El color del texto se adapta automáticamente para que tu página siga siendo legible.',
        'Understand your analytics': 'Entiende tus estadísticas',
        'Analytics shows total clicks, unique visitors, clicks per day and your top referrers.':
            'Estadísticas muestra clics totales, visitantes únicos, clics por día y tus principales referentes.',
        'Numbers are collected without cookies and without storing personal data — visitors are never tracked across days.':
            'Los números se recolectan sin cookies y sin guardar datos personales — nunca se rastrea a un visitante entre días.',
        'Copy your URL from "Share your page" in the dashboard.':
            'Copia tu URL desde "Comparte tu página" en el panel.',
        'Download your QR code as SVG — print it anywhere, it always points to your page.':
            'Descarga tu código QR en SVG — imprímelo donde quieras, siempre apunta a tu página.',
        'Report': 'Reportar',
        'Report this page': 'Reportar esta página',
        'Something broken, misleading or abusive? Let the page owner know. Reports are anonymous.':
            '¿Algo roto, engañoso o abusivo? Avísale al dueño de la página. Los reportes son anónimos.',
        "What's wrong?": '¿Qué está mal?',
        'Broken link': 'Link roto',
        'Malicious or scam': 'Malicioso o estafa',
        'Abusive content': 'Contenido abusivo',
        'Spam': 'Spam',
        'Other': 'Otro',
        'Which link? (optional)': '¿Qué link? (opcional)',
        'The whole page': 'Toda la página',
        'Details (optional)': 'Detalles (opcional)',
        'Send report': 'Enviar reporte',
        'Thanks — your report was sent.': 'Gracias — tu reporte fue enviado.',
        'You already reported this page today.': 'Ya reportaste esta página hoy.',
        'Reports': 'Reportes',
        'Report marked as resolved.': 'Reporte marcado como resuelto.',
        'No reports. All good!': 'No hay reportes. ¡Todo en orden!',
        'Resolved': 'Resuelto',
        'Open': 'Abierto',
        'Mark as resolved': 'Marcar como resuelto',
        'You have :count open report.|You have :count open reports.':
            'Tienes :count reporte abierto.|Tienes :count reportes abiertos.',
        'Review reports': 'Ver reportes',
        'Skip to content': 'Saltar al contenido',
        'Platform': 'Plataforma',
        'Country code': 'Código de país',
        'Phone number': 'Número de teléfono',
    },
    pt: {
        'An account with this email already exists. Verify your email on Nexo ID first.':
            'Já existe uma conta com este e-mail. Verifique seu e-mail no Nexo ID primeiro.',
        'Continue with Nexo ID': 'Continuar com o Nexo ID',
        'Sign-in with Nexo ID failed. Please try again.':
            'O login com o Nexo ID falhou. Tente novamente.',
        'Sign-in with Nexo ID is temporarily unavailable. Please try again later.':
            'O login com o Nexo ID está temporariamente indisponível. Tente novamente mais tarde.',
        'The sign-in request could not be validated. Please try again.':
            'Não foi possível validar a solicitação de login. Tente novamente.',
        'or': 'ou',
        ':count click|:count clicks': ':count clique|:count cliques',
        ':days days': ':days dias',
        'Accent palette': 'Paleta de destaque',
        'Access denied': 'Acesso negado',
        'Add': 'Adicionar',
        'Add link': 'Adicionar link',
        "An open-source link-in-bio page you host yourself — with visitor analytics that don't spy on anyone.":
            'Uma página link-in-bio de código aberto que você mesmo hospeda — com estatísticas de visitantes que não espionam ninguém.',
        'Analytics': 'Estatísticas',
        'Analytics without spying': 'Estatísticas sem espionar',
        'Are you sure you want to delete your account?': 'Tem certeza de que quer excluir a sua conta?',
        'Avatar': 'Avatar',
        'Avatar, banner, color palettes, solid or gradient backgrounds — with automatic dark mode and readable text guaranteed.':
            'Avatar, banner, paletas de cores, fundos sólidos ou degradês — com modo escuro automático e texto legível garantido.',
        'Back to home': 'Voltar ao início',
        'Background': 'Fundo',
        'Banner': 'Capa',
        'Bio': 'Bio',
        'Build a WhatsApp link': 'Criar um link de WhatsApp',
        'Click stats with zero cookies and zero personal data stored. No consent banner needed — private by design.':
            'Estatísticas de cliques sem cookies e sem armazenar dados pessoais. Sem banner de consentimento — privado por padrão.',
        'Clicks': 'Cliques',
        'Clicks per day': 'Cliques por dia',
        'Clicks per day, last :days days': 'Cliques por dia, últimos :days dias',
        'Color': 'Cor',
        'Copied!': 'Copiado!',
        'Copy URL': 'Copiar URL',
        'Create your page': 'Crie sua página',
        "Create your page — it's free": 'Crie sua página — é grátis',
        'Create yours': 'Crie a sua',
        'Dashboard': 'Painel',
        'Default': 'Padrão',
        'Delete': 'Excluir',
        'Delete this link?': 'Excluir este link?',
        'Remove this social icon?': 'Remover este ícone social?',
        'Design': 'Design',
        'Design updated.': 'Design atualizado.',
        'Download QR (SVG)': 'Baixar QR (SVG)',
        'Drag to reorder': 'Arraste para reordenar',
        'Edit': 'Editar',
        'Ends at (optional)': 'Termina em (opcional)',
        'Expired': 'Expirado',
        'Fast and lightweight': 'Rápido e leve',
        'Features': 'Recursos',
        'For security reasons, reload the page and try again.': 'Por segurança, recarregue a página e tente novamente.',
        'Gradient': 'Degradê',
        'Hidden': 'Oculto',
        'Hide': 'Ocultar',
        'Highlight this link': 'Destacar este link',
        'Highlighted': 'Destacado',
        'Link': 'Link',
        'Link created.': 'Link criado.',
        'Link deleted.': 'Link excluído.',
        'Link updated.': 'Link atualizado.',
        'Links': 'Links',
        'Links with superpowers': 'Links com superpoderes',
        'Log Out': 'Sair',
        'Log in': 'Entrar',
        'MIT licensed, self-hostable on cheap shared hosting (PHP + MySQL). Read the code, run your own, contribute.':
            'Licença MIT, auto-hospedável em hospedagem compartilhada barata (PHP + MySQL). Leia o código, rode o seu, contribua.',
        'No external referrers yet — clicks from your page or shared links without a referrer count as direct.':
            'Ainda não há referências externas — cliques da sua página ou de links compartilhados sem referência contam como diretos.',
        'No links yet.': 'Ainda não há links.',
        'No links yet. Add your first one!': 'Ainda não há links. Adicione o primeiro!',
        'No vendor lock-in': 'Sem dependência de plataformas',
        'Nothing here yet.': 'Ainda não há nada aqui.',
        'Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.':
            'Depois que a sua conta for excluída, todos os seus recursos e dados serão apagados permanentemente. Digite a sua senha para confirmar que quer excluir a conta definitivamente.',
        'Open source': 'Código aberto',
        'Open source on GitHub': 'Código aberto no GitHub',
        "Open-source link-in-bio page you host yourself, with visitor analytics that don't spy on anyone.":
            'Página link-in-bio de código aberto que você mesmo hospeda, com estatísticas de visitantes que não espionam ninguém.',
        'Page not found': 'Página não encontrada',
        'Per link': 'Por link',
        'Prefilled message (optional)': 'Mensagem pré-preenchida (opcional)',
        'Print it, add it to a business card or a story — it always points to your page.':
            'Imprima, coloque em um cartão de visita ou em um story — sempre aponta para a sua página.',
        'Privacy': 'Privacidade',
        'QR code that opens your page': 'Código QR que abre a sua página',
        'Remove :platform': 'Remover :platform',
        'Remove avatar': 'Remover avatar',
        'Remove banner': 'Remover banner',
        'Save': 'Salvar',
        'Schedule links by date, highlight what\'s live right now, and tease launches with a countdown.':
            'Agende links por data, destaque o que está ao vivo agora e antecipe lançamentos com uma contagem regressiva.',
        'Scheduled': 'Agendado',
        'See a live example': 'Veja um exemplo ao vivo',
        "Server-rendered pages with no trackers and no external requests. Built mobile-first, because that's where your visitors are.":
            'Páginas renderizadas no servidor, sem rastreadores nem requisições externas. Feito primeiro para o celular, porque é lá que estão suas visitas.',
        'Share your page': 'Compartilhe sua página',
        'Show': 'Mostrar',
        'Show countdown before it starts': 'Mostrar contagem regressiva antes de começar',
        'Shown as icons at the bottom of your page. Prefer a big button? Add it as a regular link instead.':
            'Aparecem como ícones no rodapé da sua página. Prefere um botão grande? Adicione como link normal.',
        'Social icon added.': 'Ícone social adicionado.',
        'Social icon removed.': 'Ícone social removido.',
        'Social icons': 'Ícones sociais',
        'Social profiles': 'Perfis sociais',
        'Solid color': 'Cor sólida',
        'Something went wrong': 'Algo deu errado',
        'Starts at (optional)': 'Começa em (opcional)',
        'Terms': 'Termos',
        'The :attribute ":input" is reserved.': 'O :attribute ":input" está reservado.',
        'The :attribute may only contain lowercase letters, numbers, hyphens and underscores.':
            'O campo :attribute só pode conter minúsculas, números, hifens e sublinhados.',
        'The :attribute must be a valid URL.': 'O campo :attribute deve ser uma URL válida.',
        'The :attribute must start with http://, https://, mailto: or tel:.':
            'O campo :attribute deve começar com http://, https://, mailto: ou tel:.',
        'The page expired': 'A página expirou',
        'The problem is on our side. Try again in a few minutes.':
            'O problema é do nosso lado. Tente novamente em alguns minutos.',
        'This will be your public page URL. Lowercase letters, numbers, hyphens and underscores.':
            'Esta será a URL pública da sua página. Minúsculas, números, hifens e sublinhados.',
        'Title': 'Título',
        'To': 'Até',
        'Too many requests': 'Muitas solicitações',
        'Top referrers': 'Principais referências',
        'Total clicks': 'Total de cliques',
        'URL': 'URL',
        'Under maintenance': 'Em manutenção',
        'Unique': 'Únicos',
        'Unique visitors': 'Visitantes únicos',
        'Use the international format, e.g. +5491122334455.': 'Use o formato internacional, ex.: +5491122334455.',
        'Use this link': 'Usar este link',
        'Use your handle without the @, e.g. yourname.': 'Use seu usuário sem o @, ex.: yourname.',
        'Used for your avatar ring and highlighted links.': 'Usado no anel do seu avatar e nos links destacados.',
        'Username': 'Nome de usuário',
        'View my page': 'Ver minha página',
        'We are making improvements. We will be back in a few minutes.':
            'Estamos fazendo melhorias. Voltamos em alguns minutos.',
        'We could not find what you were looking for. The link may have changed or the page may no longer exist.':
            'Não encontramos o que você procurava. O link pode ter mudado ou a página pode não existir mais.',
        'You already added this platform.': 'Você já adicionou esta plataforma.',
        'You do not have permission to see this page.': 'Você não tem permissão para ver esta página.',
        'You went a little too fast. Wait a moment and try again.':
            'Você foi um pouco rápido demais. Espere um momento e tente novamente.',
        'Your data.': 'Seus dados.',
        'Your domain.': 'Seu domínio.',
        'Your handle, email or URL': 'Seu usuário, e-mail ou URL',
        'Your links.': 'Seus links.',
        'Your links. Your domain. Your data.': 'Seus links. Seu domínio. Seus dados.',
        'Your look': 'Seu estilo',
        'Your page lives on your own domain and server. No platform can paywall it, break your URL or shut it down.':
            'Sua página vive no seu próprio domínio e servidor. Nenhuma plataforma pode cobrar por ela, quebrar sua URL nem encerrá-la.',
        'starts in': 'começa em',
        'Language': 'Idioma',
        'Help': 'Ajuda',
        'Help topics': 'Tópicos de ajuda',
        'What can I do in :app?': 'O que posso fazer no :app?',
        'Create your account': 'Crie sua conta',
        'Click "Create your page" and choose a username — it becomes your public URL.':
            'Clique em "Crie sua página" e escolha um nome de usuário — ele vira a sua URL pública.',
        'Verify your email address with the link we send you.':
            'Verifique seu e-mail com o link que enviamos.',
        'Your page is immediately live at /your-username.':
            'Sua página fica no ar na hora em /seu-usuario.',
        'Add and organize your links': 'Adicione e organize seus links',
        'In the dashboard, press "+ Add link" and give it a title and a URL.':
            'No painel, clique em "+ Adicionar link" e dê um título e uma URL.',
        'Drag the handle on the left of each card to reorder.':
            'Arraste a alça à esquerda de cada cartão para reordenar.',
        'Use Hide to keep a link without showing it, and Edit to change it anytime.':
            'Use Ocultar para manter um link sem exibi-lo, e Editar para alterá-lo quando quiser.',
        'Schedule, highlight and countdowns': 'Agendamento, destaques e contagens regressivas',
        'When creating or editing a link, set optional start and end dates — it publishes and unpublishes itself.':
            'Ao criar ou editar um link, defina datas de início e fim opcionais — ele se publica e despublica sozinho.',
        'Mark "Highlight this link" to give it the accent color treatment.':
            'Marque "Destacar este link" para dar a ele a cor de destaque.',
        'Enable the countdown to tease a launch: visitors see a live timer until it starts.':
            'Ative a contagem regressiva para antecipar um lançamento: os visitantes veem um cronômetro ao vivo até começar.',
        'Contact buttons and WhatsApp': 'Botões de contato e WhatsApp',
        'Links accept https://, mailto: and tel: — so a button can send an email or call you.':
            'Os links aceitam https://, mailto: e tel: — assim um botão pode enviar um e-mail ou ligar para você.',
        'Use "Build a WhatsApp link" to generate a wa.me link with a prefilled message.':
            'Use "Criar um link de WhatsApp" para gerar um link wa.me com mensagem pré-preenchida.',
        'In "Social icons", pick a platform and enter your handle, email or phone.':
            'Em "Ícones sociais", escolha uma plataforma e informe seu usuário, e-mail ou telefone.',
        'Make it yours': 'Deixe com a sua cara',
        'In Design, upload an avatar and a banner, and write your bio.':
            'Em Design, envie um avatar e um banner, e escreva sua bio.',
        'Pick an accent palette and a background: default, solid color or gradient.':
            'Escolha uma paleta de destaque e um fundo: padrão, cor sólida ou degradê.',
        'Text color adapts automatically so your page stays readable.':
            'A cor do texto se adapta automaticamente para sua página continuar legível.',
        'Understand your analytics': 'Entenda suas estatísticas',
        'Analytics shows total clicks, unique visitors, clicks per day and your top referrers.':
            'Estatísticas mostra o total de cliques, visitantes únicos, cliques por dia e suas principais referências.',
        'Numbers are collected without cookies and without storing personal data — visitors are never tracked across days.':
            'Os números são coletados sem cookies e sem armazenar dados pessoais — nenhum visitante é rastreado entre dias.',
        'Copy your URL from "Share your page" in the dashboard.':
            'Copie sua URL em "Compartilhe sua página" no painel.',
        'Download your QR code as SVG — print it anywhere, it always points to your page.':
            'Baixe seu código QR em SVG — imprima onde quiser, ele sempre aponta para a sua página.',
        'Report': 'Denunciar',
        'Report this page': 'Denunciar esta página',
        'Something broken, misleading or abusive? Let the page owner know. Reports are anonymous.':
            'Algo quebrado, enganoso ou abusivo? Avise o dono da página. As denúncias são anônimas.',
        "What's wrong?": 'O que está errado?',
        'Broken link': 'Link quebrado',
        'Malicious or scam': 'Malicioso ou golpe',
        'Abusive content': 'Conteúdo abusivo',
        'Spam': 'Spam',
        'Other': 'Outro',
        'Which link? (optional)': 'Qual link? (opcional)',
        'The whole page': 'A página inteira',
        'Details (optional)': 'Detalhes (opcional)',
        'Send report': 'Enviar denúncia',
        'Thanks — your report was sent.': 'Obrigado — sua denúncia foi enviada.',
        'You already reported this page today.': 'Você já denunciou esta página hoje.',
        'Reports': 'Denúncias',
        'Report marked as resolved.': 'Denúncia marcada como resolvida.',
        'No reports. All good!': 'Nenhuma denúncia. Tudo certo!',
        'Resolved': 'Resolvida',
        'Open': 'Aberta',
        'Mark as resolved': 'Marcar como resolvida',
        'You have :count open report.|You have :count open reports.':
            'Você tem :count denúncia aberta.|Você tem :count denúncias abertas.',
        'Review reports': 'Ver denúncias',
        'Skip to content': 'Pular para o conteúdo',
        'Platform': 'Plataforma',
        'Country code': 'Código do país',
        'Phone number': 'Número de telefone',
    },
};

// Every literal string the app hands to Laravel for translation. English is the
// source locale here (lang/es.json and lang/pt.json are keyed by the English
// text), so a key absent from BOTH laravel-lang and the `nexo` map above renders
// in English to Spanish and Portuguese visitors — silently, which is why this is
// checked rather than trusted.
const sourceStrings = () => {
    const phpFiles = (dir) =>
        readdirSync(dir).flatMap((entry) => {
            const full = join(dir, entry);
            return statSync(full).isDirectory() ? phpFiles(full) : full.endsWith('.php') ? [full] : [];
        });

    // __() and trans_choice(), plus the $fail() messages of custom rules (they
    // are translated through ->translate()). Both quote styles.
    const pattern = /(?:__|trans_choice|\$fail)\(\s*(?:'((?:[^'\\]|\\.)*)'|"((?:[^"\\]|\\.)*)")/g;
    const keys = new Set();

    for (const root of SOURCE_ROOTS) {
        for (const file of phpFiles(root)) {
            for (const match of readFileSync(file, 'utf8').matchAll(pattern)) {
                const key = match[1] !== undefined
                    ? match[1].replace(/\\'/g, "'")
                    : match[2].replace(/\\"/g, '"');

                // Not literal UI text: lang-file lookups ("nexo.help.title") and
                // interpolated keys ("legal.{$page}", resolved at runtime).
                if (/^[a-z0-9_.-]+$/.test(key) || /\{\$|\$\{/.test(key)) {
                    continue;
                }

                keys.add(key);
            }
        }
    }

    return [...keys].sort();
};

const phpExport = (value, indent = 1) => {
    const pad = '    '.repeat(indent);
    if (typeof value !== 'object' || value === null) {
        return `'${String(value).replace(/\\/g, '\\\\').replace(/'/g, "\\'")}'`;
    }
    const rows = Object.entries(value)
        .map(([k, v]) => `${pad}'${k.replace(/'/g, "\\'")}' => ${phpExport(v, indent + 1)},`)
        .join('\n');
    return `[\n${rows}\n${'    '.repeat(indent - 1)}]`;
};

// Expand dot-keys ("between.numeric") into nested objects.
const nest = (flat) => {
    const out = {};
    for (const [key, value] of Object.entries(flat)) {
        const parts = key.split('.');
        let node = out;
        while (parts.length > 1) node = node[parts.shift()] ??= {};
        node[parts[0]] = value;
    }
    return out;
};

// Output locale -> laravel-lang source locale. `pt` (canonical ecosystem code)
// carries Brazilian Portuguese content sourced from laravel-lang's `pt_BR`.
const locales = [
    { code: 'es', source: 'es' },
    { code: 'pt', source: 'pt_BR' },
];

const sourced = sourceStrings();
let failed = false;

for (const { code, source } of locales) {
    const base = `vendor/laravel-lang/lang/locales/${source}`;
    const php = JSON.parse(readFileSync(`${base}/php.json`, 'utf8'));
    const json = JSON.parse(readFileSync(`${base}/json.json`, 'utf8'));

    const groups = { auth: {}, passwords: {}, pagination: {}, validation: {} };
    for (const [key, value] of Object.entries(php)) {
        if (AUTH_KEYS.includes(key)) groups.auth[key] = value;
        else if (PASSWORD_KEYS.includes(key)) groups.passwords[key] = value;
        else if (PAGINATION_KEYS.includes(key)) groups.pagination[key] = value;
        else groups.validation[key] = value;
    }

    const merged = { ...json, ...nexo[code] };

    const missing = sourced.filter((key) => !(key in merged));
    if (missing.length > 0) {
        failed = true;
        console.error(`\n[${code}] ${missing.length} source strings have no translation (add them to the \`nexo\` map in this file):`);
        missing.forEach((key) => console.error(`  - ${key}`));
    }

    const files = { [`lang/${code}.json`]: JSON.stringify(merged, null, 4) + '\n' };
    for (const [group, entries] of Object.entries(groups)) {
        files[`lang/${code}/${group}.php`] = `<?php\n\nreturn ${phpExport(nest(entries))};\n`;
    }

    if (CHECK) {
        const stale = Object.keys(files).filter(
            (path) => !existsSync(path) || readFileSync(path, 'utf8') !== files[path],
        );

        if (stale.length > 0) {
            failed = true;
            console.error(`\n[${code}] out of date, re-run the generator without --check: ${stale.join(', ')}`);
        }

        continue;
    }

    mkdirSync(`lang/${code}`, { recursive: true });
    for (const [path, contents] of Object.entries(files)) {
        writeFileSync(path, contents);
    }

    console.log(`✓ ${code}: ${Object.keys(merged).length} JSON strings, validation/auth/passwords/pagination PHP files`);
}

if (CHECK && !failed) {
    console.log(`✓ ${sourced.length} source strings translated in es/pt, and the lang files match the generator.`);
}

process.exit(failed ? 1 : 0);
