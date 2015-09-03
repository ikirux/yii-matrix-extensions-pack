<?= BsHtml::tabs([
    [
        'label' => 'Source Messages',
        'url' => $this->createUrl('sourceMessage/index'),
        'active' => $this->id == 'sourceMessage'
    ],
    [
        'label' => 'Messages',
        'url' => $this->createUrl('message/index'),
        'active' => $this->id == 'message'
    ],
    [
        'label' => 'Languages',
        'url' => $this->createUrl('language/index'),
        'active' => $this->id == 'language'
    ]
]); ?>