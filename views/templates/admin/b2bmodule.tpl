
{block name='content'}
<div class="panel">
    <h2>{l s='B2B Module Data' mod='b2bmodule'}</h2>

    {if $b2bData}
        <table class="table">
            <thead>
                <tr>
                    <th>{l s='ID' mod='b2bmodule'}</th>
                    <th>{l s='Product ID' mod='b2bmodule'}</th>
                    <th>{l s='Customer Name' mod='b2bmodule'}</th>
                    <th>{l s='Contact Number' mod='b2bmodule'}</th>
                    <th>{l s='Location' mod='b2bmodule'}</th>
                </tr>
            </thead>
            <tbody>
                {foreach $b2bData as $row}
                    <tr>
                        <td>{$row.id_b2bmodule}</td>
                        <td>{$row.product_id}</td>
                        <td>{$row.customer_name}</td>
                        <td>{$row.contact_number}</td>
                        <td>{$row.location}</td>
                    </tr>
                {/foreach}
            </tbody>
        </table>

{include file='./pagination.tpl' paginationId='b2bmodule_pagination'}

    {else}
        <p>{l s='No data available.' mod='b2bmodule'}</p>
    {/if}
</div>
{/block}
