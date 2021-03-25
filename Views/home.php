<div class="wrap">
	
	<h1>Site Security Headers</h1>
	
	<!-- Welcome Panel, borrowed from the Dashboard->Home page -->
	<div class="welcome-panel site-security-headers-welcome-panel">
		
		<!-- Main Content -->
		<div class="welcome-panel-content">
			
			<!-- Content Description-->
			<p class="about-description">Details about your sites security headers are listed below</p>
			
			<!-- Main Container -->
			<div class="welcome-panel-column-container">
				
				<!-- Site Grade -->
				<?php include 'header/grade.php'; ?>
				
				<!-- Headers Tested -->
				<div class="welcome-panel-column">
					<?php include 'header/tested.php'; ?>
				</div>
				
				<!-- Header Tables -->
				<?php if (!empty($Home->site_headers)): ?>
					<div class="site-security-headers-clearfix">
						
						<!-- Missing Headers -->
						<?php if (!empty($Home->headers_missing())): ?>
							<?php include 'section/missing.php'; ?>
						<?php endif; ?>
						<!-- /Missing Headers -->
						
						
						<!-- Invalid Headers -->
						<?php if (!empty($Home->headers_invalid())): ?>
							<?php include 'section/invalid.php'; ?>
						<?php endif; ?>
						<!-- /Invalid Headers -->
						
						
						<!-- All Headers -->
						<?php include 'section/all.php'; ?>
						<!-- /All Headers -->
						
					</div>
				<?php endif; ?>
				
			</div>
			<!-- /Main Container -->
		</div>
		<!-- /Main Content -->
	</div>
	<!-- /Welcome Panel -->
</div>
