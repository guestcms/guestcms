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
                    ->renderForm()
            !!}
        </div>
    </div>
</section>
