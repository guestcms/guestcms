<section class="pt-120 pb-120 bg-white">
    <div class="container">
        <div class="pt-80">
            {!!
                $form
                    ->modify('submit', 'submit', [
                        'attr' => [
                            'class' => 'mt-4 main-btn btn-filled btn-block',
                        ],
                    ])
                    ->modify('remember', 'html', ['html' => sprintf('<div class="col-6"><div class="custom-checkbox">
                            <label><input type="checkbox" name="remember" id="remember-me">
                            %s<span></span></label>
                        </div></div>', __('Remember me'))], true)
                    ->renderForm()
            !!}
        </div>
     </div>
</section>
