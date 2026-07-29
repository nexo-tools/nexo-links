<?php

// Legal pages (privacy + terms), rendered by legal/show. Translation of the
// Spanish source in lang/es/legal.php — keep the three files in step.
//
// NOT reviewed by a lawyer. Written to describe accurately what this codebase
// actually does, so that a review, if the owner wants one, starts from
// something true rather than from a template full of clauses about data the
// app never collects.
return [
    'updated' => 'Última atualização: 28 de julho de 2026',

    // Rendered only when NEXO_LEGAL_OPERATOR / NEXO_LEGAL_CONTACT are set.
    'operator' => [
        'h' => 'Quem opera esta instância',
        'p' => 'Esta instância é operada por :operator.',
        'contact' => 'Para qualquer questão sobre seus dados, escreva para :contact.',
    ],

    'privacy' => [
        'title' => 'Privacidade',
        'intro' => 'Esta instância do Nexo Links é open source e self-hosted. Coletamos o mínimo para que a sua página de links funcione, e nada mais. Não há cookies de rastreamento, nem análise de terceiros, nem requisições a servidores alheios a partir de nenhuma página.',
        'sections' => [
            [
                'h' => 'O que guardamos da sua conta',
                'p' => 'Seu nome, seu e-mail e uma versão criptografada (hash) da senha, além da data em que você verificou o e-mail. O e-mail serve para verificar a conta e recuperar o acesso, e nada mais: não enviamos newsletters. Se esta instância tiver o login com Nexo ID habilitado e você o usar, guardamos também o identificador que esse serviço nos dá para reconhecer você.',
            ],
            [
                'h' => 'Sua página pública é pública',
                'p' => 'Seu nome de usuário, sua bio, sua foto, sua capa, seus links (título e destino) e seus ícones sociais são publicados no endereço /seuusuario deste site para qualquer pessoa que o tenha, e a página aparece no sitemap para que os buscadores a indexem. A foto e a capa ficam em uma pasta pública do servidor: quem tiver a URL delas as vê, mesmo depois de você remover o link. Não coloque ali nada que não queira publicar — um telefone ou um e-mail como ícone social também fica à vista.',
            ],
            [
                'h' => 'O que medimos das visitas',
                'p' => 'Apenas os cliques nos seus links. De cada clique guardamos qual link foi, quando, uma impressão anônima do visitante e, se veio de outro site, somente o domínio de origem (por exemplo "instagram.com", sem o caminho nem os parâmetros). Os cliques que saem da sua própria página são guardados como diretos. Não guardamos o IP, nem o navegador, nem a localização, nem nenhum identificador que persista.',
            ],
            [
                'h' => 'Por que a impressão não pode seguir você',
                'p' => 'A impressão é o resultado de aplicar SHA-256 a quatro coisas juntas: a chave secreta desta instalação, a data de hoje, o seu IP e o seu navegador. A única coisa guardada é esse resultado; o IP e o navegador são descartados na hora e não podem ser recuperados a partir dele. Como a data entra no cálculo, amanhã a mesma pessoa produz uma impressão completamente diferente e não existe forma de emparelhar as duas: serve para contar "quantas pessoas distintas clicaram hoje" e nada mais. E como a chave é própria desta instalação, também não pode ser cruzada com a de outro site.',
            ],
            [
                'h' => 'Cookies',
                'p' => 'Somente os necessários para o site funcionar: o de sessão e o de proteção de formulários (emitidos quando você entra na sua conta), e dois que lembram a sua preferência de idioma e de tema claro/escuro. Esses dois últimos trafegam sem criptografia e com escopo no domínio pai de propósito, para que a sua escolha seja respeitada em todas as ferramentas Nexo; não carregam dados pessoais. Nenhum deles serve para publicidade ou rastreamento, e por isso você não verá um banner de consentimento.',
            ],
            [
                'h' => 'Enquanto a sua sessão está aberta',
                'p' => 'O registro de sessão do servidor guarda o seu IP e o seu navegador enquanto a sessão está viva, para poder encerrá-la e detectar abusos. É apagado ao sair da conta ou quando expira. Isso só acontece se você tem conta e fez login: visitar uma página pública não cria nenhuma sessão.',
            ],
            [
                'h' => 'Denúncias',
                'p' => 'Qualquer pessoa pode denunciar uma página ou um link sem se cadastrar. De uma denúncia guardamos o motivo, o comentário opcional que você escrever, a qual página ou link ela aponta e a mesma impressão diária anônima, usada apenas para não aceitar a mesma denúncia duas vezes no mesmo dia. Não pedimos e-mail nem nome a quem denuncia. O dono da página vê o motivo e o comentário.',
            ],
            [
                'h' => 'E-mails',
                'p' => 'Os únicos e-mails que enviamos são os da conta: verificação do e-mail e recuperação de senha. Saem por um provedor de e-mail externo, que necessariamente processa o endereço de destino e o conteúdo para poder entregá-los.',
            ],
            [
                'h' => 'Nada externo nas páginas',
                'p' => 'Nenhuma página carrega fontes, scripts, imagens ou CDNs de terceiros: tudo é servido a partir deste domínio e a política de segurança de conteúdo do site bloqueia isso explicitamente. Isso significa que visitar a sua página não avisa mais ninguém de que você a visitou. Existe um contador opcional do ecossistema Nexo, desligado por padrão, que envia apenas o sinal de "uma visita a esta ferramenta", sem identificar ninguém nem para qual página foi.',
            ],
            [
                'h' => 'Por quanto tempo e como se apaga',
                'p' => 'Os dados vivem enquanto a sua conta existir. Ao apagar a conta, apagam-se em cascata a sua página, os seus links, os seus ícones sociais, os cliques registrados e as denúncias recebidas. Ao trocar ou remover a sua foto ou a sua capa, o arquivo anterior é apagado do servidor.',
            ],
            [
                'h' => 'Seus direitos',
                'p' => 'Você pode ver e editar os seus dados no seu próprio painel, e apagar tudo apagando a conta. Para pedir acesso, correção ou exclusão por outra via, escreva a quem opera esta instância pelo contato que aparece no rodapé desta página.',
            ],
            [
                'h' => 'Outras instâncias',
                'p' => 'O Nexo Links pode ser instalado em qualquer servidor. Cada instalação é independente, tem a sua própria chave e é responsável pelos seus próprios dados: esta política fala apenas desta instância.',
            ],
        ],
    ],

    'terms' => [
        'title' => 'Termos de uso',
        'intro' => 'Ao usar esta instância do Nexo Links você aceita o que segue. É um serviço gratuito, oferecido como está.',
        'sections' => [
            [
                'h' => 'O que é o serviço',
                'p' => 'Uma ferramenta para publicar uma página com todos os seus links em um endereço próprio do tipo /seuusuario, com estatísticas de cliques, agendamento de links por data e personalização visual. Não hospedamos o seu conteúdo: hospedamos os links que apontam para ele.',
            ],
            [
                'h' => 'Sua conta e seu nome de usuário',
                'p' => 'Você precisa de uma conta e de verificar o seu e-mail para publicar. Você é responsável por manter a sua senha em segurança e pelo que for feito a partir da sua conta. Há nomes de usuário que não podem ser registrados: os que colidem com os endereços da própria aplicação e os que serviriam para se passar pelo site. Quem opera esta instância pode reaver um nome de usuário que esteja sendo usado para se passar por uma pessoa ou marca.',
            ],
            [
                'h' => 'Responsabilidade sobre os seus links',
                'p' => 'O conteúdo para o qual os seus links apontam é responsabilidade sua, não nossa. Por segurança de quem clica, só são aceitos endereços http, https, mailto e tel: qualquer outro esquema (javascript:, data:, file: e semelhantes) é recusado ao salvar, porque são os usados para executar código no navegador da visita.',
            ],
            [
                'h' => 'Uso indevido',
                'p' => 'Não é permitido usar uma página para phishing, malware, golpes, falsidade de identidade, spam ou conteúdo ilegal. Qualquer visitante pode denunciar uma página ou um link específico pelo link de denúncia dessa página, e quem opera esta instância pode derrubar a página: ela deixa de estar disponível e os seus links deixam de redirecionar.',
            ],
            [
                'h' => 'Disponibilidade',
                'p' => 'O serviço é oferecido sem garantias de disponibilidade. Fazemos o razoável para mantê-lo no ar, mas pode haver interrupções, e um endereço que você compartilhou pode ficar sem resposta durante elas.',
            ],
            [
                'h' => 'Limite de responsabilidade',
                'p' => 'Quem opera esta instância não se responsabiliza por danos decorrentes do uso do serviço, incluindo links que deixem de funcionar, estatísticas que se percam ou interrupções do serviço.',
            ],
            [
                'h' => 'Software livre',
                'p' => 'O Nexo Links é distribuído com licença MIT: você pode ler o código, modificá-lo e hospedar a sua própria instância. O software é entregue sem garantias, conforme essa licença indica.',
            ],
            [
                'h' => 'Mudanças',
                'p' => 'Estes termos podem mudar. A data acima indica a última atualização.',
            ],
        ],
    ],
];
