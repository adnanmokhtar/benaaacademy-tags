@extends("admin::layouts.master")

@section("content")

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
            <h2>
                <i class="fa fa-tags"></i>
                {{ trans("tags::tags.tags") }}
            </h2>
            <ol class="breadcrumb">
                <li>
                    <a href="{{ route("admin") }}">{{ trans("admin::common.admin") }}</a>
                </li>
                <li>
                    <a href="{{ route("admin.tags.show") }}">{{ trans("tags::tags.tags") }}
                        ({{ $tags->total() }})</a>
                </li>
            </ol>
        </div>
        <div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 text-right">
            <a href="{{ route("admin.tags.create") }}" class="btn btn-primary btn-labeled btn-main">
                <span class="btn-label icon fa fa-plus"></span>
                {{ trans("tags::tags.add_new") }}</a>
        </div>
    </div>


    <div class="wrapper wrapper-content fadeInRight">
        <div id="content-wrapper">
            @include("admin::partials.messages")
            <form action="" method="get" class="filter-form">
                <input type="hidden" name="per_page" value="{{ Request::get('per_page') }}"/>

                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group">
                            <select name="sort" class="form-control chosen-select chosen-rtl">
                                <option
                                    value="name"
                                    @if ($sort == "name") selected='selected' @endif>{{ ucfirst(trans("tags::tags.attributes.name")) }}</option>
                                <option
                                    value="created_at"
                                    @if ($sort == "date") selected='selected' @endif>{{ ucfirst(trans("tags::tags.attributes.date")) }}</option>
                            </select>
                            <select name="order" class="form-control chosen-select chosen-rtl">
                                <option
                                    value="DESC"
                                    @if (Request::get("order") == "DESC") selected='selected' @endif>{{ trans("tags::tags.desc") }}</option>
                                <option
                                    value="ASC"
                                    @if (Request::get("order") == "ASC") selected='selected' @endif>{{ trans("tags::tags.asc") }}</option>
                            </select>
                            <button type="submit"
                                    class="btn btn-primary">{{ trans("tags::tags.order") }}</button>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">

                    </div>
                    <div class="col-lg-4 col-md-4">
                        <form action="" method="get" class="search_form">


                            <div class="input-group">
                                <div class="autocomplete_area">
                                    <input type="text" name="q" value="{{ Request::get("q") }}"
                                           autocomplete="off"
                                           placeholder="{{ trans("tags::tags.search_tags") }} ..."
                                           class="form-control linked-text">

                                    <div class="autocomplete_result">
                                        <div class="result_body"></div>
                                    </div>

                                </div>

                                <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                        </span>

                            </div>


                        </form>
                    </div>
                </div>
            </form>
            <form action="" method="post" class="action_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>
                            <i class="fa fa-tags"></i>
                            {{ trans("tags::tags.tags") }}
                        </h5>
                    </div>
                    <div class="ibox-content">
                        @if (count($tags))
                            <div class="row">

                                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12 action-box">
                                    <select name="action" class="form-control pull-left">
                                        <option value="-1"
                                                selected="selected">{{ trans("tags::tags.bulk_actions") }}</option>
                                        <option value="delete">{{ trans("tags::tags.delete") }}</option>
                                    </select>
                                    <button type="submit"
                                            class="btn btn-primary pull-right">{{ trans("tags::tags.apply") }}</button>
                                </div>

                                <div class="col-lg-6 col-md-4 hidden-sm hidden-xs"></div>

                                <div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
                                    <select class="form-control per_page_filter">
                                        <option value="" selected="selected">-- {{ trans("tags::tags.per_page") }}--
                                        </option>
                                        @foreach (array(10, 20, 30, 40, 60, 80, 100, 150) as $num)
                                            <option
                                                value="{{ $num }}"
                                                @if ($num == $per_page) selected="selected" @endif>{{ $num }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table cellpadding="0" cellspacing="0" border="0"
                                       class="table table-striped table-hover">
                                    <thead>
                                    <tr>
                                        <th style="width:35px">
                                            <input type="checkbox" class="i-checks check_all" name="ids[]"/>
                                        </th>
                                        <th>{{ trans("tags::tags.attributes.name") }}</th>
                                        <th>{{ trans("tags::tags.attributes.date") }}</th>
                                        <th>{{ trans("tags::tags.actions") }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($tags as $tag)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="i-checks" name="id[]"
                                                       value="{{ $tag->id }}"/>
                                            </td>

                                            <td>
                                                <a data-toggle="tooltip" data-placement="bottom" class="text-navy"
                                                   title="{{ trans("tags::tags.edit") }}"
                                                   href="{{ route("admin.tags.edit", array("id" => $tag->id)) }}">
                                                    <strong>{{ $tag->name }}</strong>
                                                </a>
                                            </td>

                                            <td>
                                                <small>{{ $tag->created_at->render() }}</small>
                                            </td>

                                            <td class="center">
                                                <a data-toggle="tooltip" data-placement="bottom"
                                                   title="{{ trans("tags::tags.edit") }}"
                                                   href="{{ route("admin.tags.edit", array("id" => $tag->id)) }}">
                                                    <i class="fa fa-pencil text-navy"></i>
                                                </a>
                                                <a
                                                    title="{{ trans("tags::tags.delete") }}" class="ask delete_tag"
                                                    data-tag-id="{{ $tag->id }}"
                                                    message="{{ trans("tags::tags.sure_delete") }}"
                                                    href="{{ URL::route("admin.tags.delete", array("id" => $tag->id)) }}">
                                                    <i class="fa fa-times text-navy"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    {{ trans("tags::tags.page") }}
                                    {{ $tags->currentPage() }}
                                    {{ trans("tags::tags.of") }}
                                    {{ $tags->lastPage() }}
                                </div>
                                <div class="col-lg-12 text-center">
                                    {{ $tags->appends(Request::all())->render() }}
                                </div>
                            </div>
                        @else
                            {{ trans("tags::tags.no_records") }}
                        @endif
                    </div>
                </div>
            </form>
        </div>

    </div>

@stop

@section("footer")

    <script>

        $(document).ready(function () {

            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            $('.check_all').on('ifChecked', function (event) {
                $("input[type=checkbox]").each(function () {
                    $(this).iCheck('check');
                    $(this).change();
                });
            });

            $('.check_all').on('ifUnchecked', function (event) {
                $("input[type=checkbox]").each(function () {
                    $(this).iCheck('uncheck');
                    $(this).change();
                });
            });

            $(".filter-form input[name=per_page]").val($(".per_page_filter").val());

            $(".per_page_filter").change(function () {
                var base = $(this);
                var per_page = base.val();
                $(".filter-form input[name=per_page]").val(per_page);
                $(".filter-form").submit();
            });

        });
    </script>

@stop

