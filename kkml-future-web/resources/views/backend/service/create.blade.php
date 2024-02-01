@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h5 class="mb-0 h6">{{translate('Add New Service')}}</h5>
</div>
<div class="">
    <form class="form form-horizontal mar-top" action="{{route('service.store')}}" method="POST" enctype="multipart/form-data" id="choice_form">
        <div class="row gutters-5">
            <div class="col-lg-8">
                @csrf
                <input type="hidden" name="added_by" value="admin">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Service Information')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Service Name')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="name" placeholder="{{ translate('Service Name') }}" onchange="update_sku()" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Category')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="service_category_id" id="category_id" data-live-search="true" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Tags')}} <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" class="form-control aiz-tag-input" name="tags[]" placeholder="{{ translate('Type and hit enter to add a tag') }}" required>
                                <small class="text-muted">{{translate('This is used for search. Input those words by which cutomer can find this product.')}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Service Images')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{translate('Thumbnail Image')}} <small>(300x300)</small></label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="thumbnail_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                                <small class="text-muted">{{translate('This image is visible in all product box. Use 300x300 sizes image. Keep some blank space around main object of your image as we had to crop some edge in different devices to make it responsive.')}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Service Videos')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Video Provider')}}</label>
                            <div class="col-md-8">
                                <select class="form-control aiz-selectpicker" name="video_provider" id="video_provider">
                                    <option value="youtube">{{translate('Youtube')}}</option>
                                    <option value="dailymotion">{{translate('Dailymotion')}}</option>
                                    <option value="vimeo">{{translate('Vimeo')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Video Link')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="video_link" placeholder="{{ translate('Video Link') }}">
                                <small class="text-muted">{{translate("Use proper link without extra parameter. Don't use short share link/embeded iframe code.")}}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Service Description')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                            <div class="col-md-8">
                                <textarea class="aiz-text-editor" name="description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Contact Address')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Contact Info.')}}</label>
                            <div class="col-md-8">
                                <textarea class="aiz-text-editor" name="address"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('SEO Meta Tags')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Meta Title')}}</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="meta_title" placeholder="{{ translate('Meta Title') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-from-label">{{translate('Description')}}</label>
                            <div class="col-md-8">
                                <textarea name="meta_description" rows="8" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label" for="signinSrEmail">{{ translate('Meta Image') }}</label>
                            <div class="col-md-8">
                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                    </div>
                                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                    <input type="hidden" name="meta_img" class="selected-files">
                                </div>
                                <div class="file-preview box sm">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

            </div>
            <div class="col-12">
                <div class="btn-toolbar float-right mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group mr-2" role="group" aria-label="Third group">
                        <button type="submit" name="button" value="unpublish" class="btn btn-primary">{{ translate('Save & Unpublish') }}</button>
                    </div>
                    <div class="btn-group" role="group" aria-label="Second group">
                        <button type="submit" name="button" value="publish" class="btn btn-success">{{ translate('Save & Publish') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection

@section('script')

<script type="text/javascript">

    function add_more_customer_choice_option(i, name){
        $('#customer_choice_options').append('<div class="form-group row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="'+i+'"><input type="text" class="form-control" name="choice[]" value="'+name+'" placeholder="{{ translate('Choice Title') }}" readonly></div><div class="col-md-8"><input type="text" class="form-control aiz-tag-input" name="choice_options_'+i+'[]" placeholder="{{ translate('Enter choice values') }}" data-on-change="update_sku"></div></div>');

    	AIZ.plugins.tagify();
    }

	$('#choice_attributes').on('change', function() {
		$('#customer_choice_options').html(null);
		$.each($("#choice_attributes option:selected"), function(){
            add_more_customer_choice_option($(this).val(), $(this).text());
        });
		update_sku();
	});


</script>

@endsection
