<?php
$name = 'interior-card-cluster';
$title = get_field('title');

$cluster_blocks = ['top_left', 'top_right', 'bottom_left_1', 'bottom_left_2', 'bottom_left_3', 'bottom_center', 'bottom_right_1', 'bottom_right_2'];
$display_blocks = array();

foreach ($cluster_blocks as $cluster_block) {
    $card_type = get_field($cluster_block . '_card_type');
    $display_blocks[$cluster_block]['card_type'] = $card_type;
    $display_blocks[$cluster_block]['description'] = get_field($cluster_block . '_description');

    if ($card_type == 'image') {
        $display_blocks[$cluster_block]['image'] = get_field($cluster_block . '_image');
        $display_blocks[$cluster_block]['col_classes'] = 'bg-image';
    } 

    if ($card_type == 'icon') {
        $display_blocks[$cluster_block]['icon_type'] = get_field($cluster_block . '_icon_type');
        $display_blocks[$cluster_block]['background_color'] = get_field($cluster_block . '_background_color') ?: 'white';
        $display_blocks[$cluster_block]['col_classes'] = 'bg-'.$display_blocks[$cluster_block]['background_color'];
    }
}
?>

<?php include( locate_template( 'template-parts/blocks/variables.php', false, false ) ); ?>

<section id="<?php echo esc_attr($id); ?>" class="section-card-cluster <?php echo esc_attr($classes); ?>" style="<?php echo esc_attr($styles); ?>">
    <div class="card-cluster-content <?php echo $container_width; ?> pt-<?php echo $padding_top; ?> pb-<?php echo $padding_bottom; ?>">
    <?php if($title) { ?>
        <div class="row title-row">
            <div class="col-12 col-md-7 content-col<?php echo $title_alignment == 'center' || $text_alignment == 'center' ? ' mx-auto' : ''; ?>">
                <?php echo vc523_seo_heading_output($title_tag, $title, $title_classes, 'title mb-0'); ?>
            </div>
        </div>
        <?php } ?>
        
        <div class="card-cluster-container">
        <?php foreach ($cluster_blocks as $cluster_block) { 
            $item_location = str_replace('_', '-', $cluster_block); ?>
            <div class="cluster-item <?php echo $item_location; ?>">
                <?php if ($display_blocks[$cluster_block]['card_type'] == 'image') { 
                    $background_image = $display_blocks[$cluster_block]['image']; ?>
                    <div class="cluster-item-wrapper <?php echo $display_blocks[$cluster_block]['col_classes']; ?>" ontouchstart 
                        style="background-image: url(<?php echo $background_image['url']; ?>">
                        <div class="text-wrapper">
                            <div class="description"><?php echo $display_blocks[$cluster_block]['description']; ?></div>
                        </div>
                        <div class="color-overlay"></div>
                    </div>
                <?php } ?>
                <?php if ($display_blocks[$cluster_block]['card_type'] == 'icon') { 
                    $icon_type = $display_blocks[$cluster_block]['icon_type']; ?>
                    <div class="cluster-item-wrapper icon-<?php echo $icon_type;?> <?php echo $display_blocks[$cluster_block]['col_classes']; ?>" ontouchstart>
                        <?php include( locate_template( 'template-parts/blocks/card-cluster-animations.php', false, false ) ); ?>
                        <div class="text-wrapper">
                            <div class="description"><?php echo $display_blocks[$cluster_block]['description']; ?></div>
                        </div>
                    </div>
                <?php } ?>
                
            </div>
        <?php } ?>
        </div>
    </div>
</section>
