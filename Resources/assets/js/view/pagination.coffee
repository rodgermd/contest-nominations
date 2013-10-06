$ =>
  @PaginationView = Backbone.View.extend
    template          : _.template $( '#pagination-template' ).html()
    initialize        : ->
      @$el.addClass( 'pagination pull-right' ).prependTo $( '.contest-members-header' )
    events            :
      'click span' : 'handle_page_click'
    render            : ( page, pages ) ->
      @$el.html if pages == 1 then '' else @template( current : page, pages : pages )
    handle_page_click : ( e ) ->
      $e = $( e.currentTarget )
      page = $e.data 'page'
      $('.members-list-view').trigger 'contest.switch-page', page