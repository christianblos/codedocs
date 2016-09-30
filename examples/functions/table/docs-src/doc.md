{{parse(text: table(
    of: ['Number 1', 'Number 2', 'Number 3'],
    cols: [
        'Nr.'    => '{{ replace(text: "%item%", using: ["Number " => ""]) }}',
        'Value ' => '%item%'
    ]
))}}
