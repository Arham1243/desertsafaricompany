<div x-data="schemaManager()" x-init="init(JSON.parse('{{ addslashes(json_encode($schema)) }}'))">
    <div class="row">
        <div class="col-md-7">
            <div class="form-box">
                <div class="form-box__header">
                    <div class="title">Schema Fields</div>
                </div>
                <div class="form-box__body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-fields">
                                <label class="title">@context</label>
                                <input type="text" x-model="schema['@context']" name="schema[@context]"
                                    class="field" placeholder="https://schema.org">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-box">
                <div class="form-box__header">
                    <div class="title">JSON Preview</div>
                </div>
                <div class="form-box__body">
                    <div class="preview-box"
                        style="background: #f5f5f5; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto;">
                        <pre style="margin: 0; white-space: pre-wrap; word-wrap: break-word;" x-text="jsonPreview()"></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function schemaManager() {
        return {
            schema: {
                '@context': 'https://schema.org',
            },
            init(initialSchema) {
                if (initialSchema && Object.keys(initialSchema).length > 0) {
                    this.schema = initialSchema;
                }
            },
            jsonPreview() {
                return JSON.stringify(this.schema, null, 2);
            }
        }
    }
</script>
