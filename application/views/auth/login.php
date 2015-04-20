<?php echo form_open('authentication/login', array('class' => 'form-signin', 'id' => 'login')); ?>

         
          <h1>
              Login
          </h1>

          <div class="alert alert-danger hidden"></div>
          <?php echo validation_errors(); ?>
          <?php if($this->session->flashdata('status')) { ?><p class="status"><?=$this->session->flashdata('status');?></p><?php } ?>
          <input type="text" name="email" id="email" placeholder="Email" required autofocus><br />
          <input type="password" name="password" id="password" placeholder="Password" required><br />
          <button type="submit">SUBMIT</button>
        </form>