<section id="block-slideshow">
    <div class="container" style="">
        <div class="row">
			<ul class="pgwSlider">
				<li>
					<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/carrusel/carrusel1-a.jpg" height="320" width="1024" alt="">
				</li>
				<li>
					<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/carrusel/carrusel1-b.jpg" height="320" width="1022" alt="">
				</li>
				<li>
					<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/carrusel/carrusel1-c.jpg" height="320" width="1022" alt="">
				</li>
				<li>
					<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/carrusel/carrusel1-d.jpg" height="320" width="1022" alt="">
				</li>
				<li>
					<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/carrusel/carrusel1-e.jpg" height="320" width="1022" alt="">
				</li>
				<li>
					<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/carrusel/carrusel1-f.jpg" height="320" width="1022" alt="">
				</li>
				<li>
					<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/carrusel/carrusel1-g.jpg" height="320" width="1022" alt="">
				</li>
				<li>
					<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/carrusel/carrusel1-h.jpg" height="320" width="1022" alt="">
				</li>
			</ul>
		</div>
	</div>
</section>

<section id="block-main">
    <div class="container">

        <div class="row">
        	<div id="main-content" class="col-md-12 main-content">
		        <div id="content">

        			<div id="system-message-container"></div>

					<div class="category-view highlight-shop grid-pro">
						<div class="row">

							<?php foreach ($categorias as $registro) { ?>

							<div class="category col-xs-12 col-sm-3">
								<div class="cat-thumb-item cat-thumb-item-grid">
									<img src="<?= base_url() ?>assets/front/jv-marvel/imagenes/categorias/<?=$registro['foto']?>" alt="">
									<div class="cat-caption bg-color7">
										<h3><a href="<?= base_url() ?>c/<?=$registro['carpeta']?>" title="<?=$registro['nombre']?>"><?=$registro['nombre']?></a></h3>
										<p><a href="<?= base_url() ?>c/<?=$registro['carpeta']?>" title="<?=$registro['nombre']?>">Ver más</a></p>
									</div>
								</div>
							</div>

							<?php } ?>
							
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

