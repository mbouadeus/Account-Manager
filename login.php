<div class="main_content">
    <form method="POST" action="<?php echo $form_submit; ?>" class="login_width bg-dark pt-4 pb-2 px-5 my-5 rounded mx-auto" novalidate>
        <h1 class="text-uppercase font-weight-bold" align="center">Login Account</h1>
        <div class="form-row mt-3">
            <div class="col-md-12 mb-3">
                <label for="validationTooltip01" class="font-weight-bold">Email</label>
                <input type="email" class="form-control" id="validationTooltip01" name="email" value="<?php if (isset($email)) echo $email;?>" required>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-12 mb-3">
                <label for="validationTooltip03" class="font-weight-bold">Password</label>
                <input type="password" class="form-control" id="validationTooltip03" name="password" required>
            </div>
        </div>
        <p align="center"><button class="btn btn-primary mt-4" type="submit">Log in</button></p>
    </form>
</div>

