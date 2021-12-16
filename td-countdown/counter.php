<?php

/**
 * @Project          : TD - Countdown plugin
 * @File             : counter.php
 * @Author           : Tompi Developments <tompidev>
 * @Website          : https://tompidev.com/
 * @Github           : https://github.com/tompidev
 * @Email            : support@tompidev.com
 *
 * @Last-modified    : 2021-Dec-14 21:05:17 CET
 * @Release          : 1.0.0.2150
 * @Licence          : MIT
 */
?>
<section id="countdown3">
	<div class="container">
		<div class="row">
			<div class="col-lg-10">
				<?php if ($this->getValue('countdownHeading')) : ?>
					<div class="mbr-section-title mb-3 text-<?php echo $this->getValue('countdownAlign') ?> mbr-fonts-style" style="font-size: <?php echo $this->getValue('countdownHeadingSize') ?>rem; line-height: <?php echo $this->getValue('countdownHeadingSize') ?>rem">
						<?php echo $this->getValue('countdownHeading') ?>
					</div>
				<?php endif ?>

				<div class="countdown-cont text-<?php echo $this->getValue('countdownAlign') ?> mb-3">
					<div class="daysCountdown col-xs-3 col-sm-3 col-md-3" title="<?php echo $L->get('Days') ?>"></div>
					<div class="hoursCountdown col-xs-3 col-sm-3 col-md-3" title="<?php echo $L->get('Hours') ?>"></div>
					<div class="minutesCountdown col-xs-3 col-sm-3 col-md-3" title="<?php echo $L->get('Minutes') ?>"></div>
					<div class="secondsCountdown col-xs-3 col-sm-3 col-md-3" title="<?php echo $L->get('Seconds') ?>"></div>
					<div class="countdown" data-due-date="<?php echo $this->getValue('date') ?>"></div>
				</div>

				<?php if ($this->getValue('countdownMessage')) : ?>
					<div class="mbr-text text-<?php echo $this->getValue('countdownAlign') ?>">
						<?php echo htmlspecialchars_decode($this->getValue('countdownMessage')) ?>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</section>