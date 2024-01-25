{extends file='page.tpl'}

{block name='content'}
    {if $message != null}
        <div class="alert alert-info" role="alert">
            <p class="alert-text">
                {$message}
            </p>
        </div>
    {/if}

    <p class="h2">Bulk Order Enquiry</p>
    <form action="{$link->getModuleLink('b2bmodule', 'b2bform', [], true)|escape:'html':'UTF-8'}" method="post">
        <input type="hidden" name="product_id" value="{$product_id|default:''|escape:'html':'UTF-8'}">
        
        {if isset($product_name)}
            <p><strong>Product Name:</strong> <span>{$product_name|escape:'html':'UTF-8'}</span></p>
        {/if}

        <div class="form-group">
            <label class="form-control-label" for="customer_name">Name</label>
            <input type="text" required class="form-control" id="customer_name" name="customer_name"/>
        </div>

        <div class="form-group">
            <label class="form-control-label" for="contact_number">Contact Number</label>
            <input type="number" required class="form-control" id="contact_number" name="contact_number"/>
        </div>

        <div class="form-group">
            <label class="form-control-label" for="location">City</label>
            <input type="text" required class="form-control" id="location" name="location"/>
        </div>
        
        <button type="submit" name="submitB2bForm" class="btn btn-primary">Submit</button>
    </form>
{/block}
