@if (!$content)
    <div class="editor-section mar-y">
        <div class="container">
            <div class="editor-content">
                <h2><strong>Empowering Businesses Through Digital Innovation</strong></h2>
                <p>We craft modern, scalable web solutions that help brands stay ahead. From concept to code, we align
                    every pixel and function with your business goals.We craft modern, scalable web solutions that help
                    brands stay ahead. From concept to code, we align every pixel and function with your business
                    goals.We craft modern, scalable web solutions that help brands stay ahead. From concept to code, we
                    align every pixel and function with your business goals.</p>
                <h4><strong>What We Deliver:</strong></h4>
                <ul>
                    <li>Custom web and app development tailored to your needs</li>
                    <li>Scalable backend systems that grow with your business</li>
                    <li>High-performance frontends with stunning UI/UX</li>
                    <li>Full-stack solutions with clean, maintainable code</li>
                </ul>
                <p>Whether you're launching a startup or scaling an enterprise, we bring the tools and expertise to
                    elevate your digital presence and deliver real results.</p>
                <p>We don’t just build websites — we engineer experiences. With a relentless focus on performance,
                    design, and usability, we turn ideas into real-world solutions.</p>
                <p><strong>Let’s build something that stands out and performs — because average isn’t in our
                        vocabulary.</strong></p>
            </div>
        </div>
    </div>
@else
    <div class="editor-section my-5">
        <div class="container">
            <div class="text-document">
                {!! $content->content ?? '' !!}
            </div>
        </div>
    </div>
@endif
