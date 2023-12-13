<?php
// Create id attribute allowing for custom "anchor" value.
$id = 'home-hero-' . $block['id'];
if (!empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'home-hero';
if (!empty($block['className'])) {
    $className .= ' ' . $block['className'];
}
if (!empty($block['align'])) {
    $className .= ' align' . $block['align'];
}

$lock = array(
    'remove' => false,
    'move' => true
);

$template = array(
    array('core/heading', array('placeholder' => 'Optimal Solutions. Laser Accuracy.', 'level' => 1)),
    array('core/media-text', '', array(
        array('core/paragraph'),
        array('core/buttons', array(
        'lock' => $lock
        )
        )
    )),

);

?>

<div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
    <InnerBlocks templateLock="all" template="<?php echo esc_attr(wp_json_encode($template)); ?>" />
</div>
