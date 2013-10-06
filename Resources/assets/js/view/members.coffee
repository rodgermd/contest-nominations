$ =>
  @MembersView = Backbone.View.extend
    script_element     : $( '#members-view-template' )
    template           : null
    members            : null
    contest            : null
    member_images_view : null
    pagination_view    : null
    allow_vote         : null
    page               : 1
    per_page           : 8
    initialize         : ->
      @template = _.template @script_element.html()
      @contest = @script_element.data 'contest'
      @$el.addClass 'members-list-view'
      @$el.appendTo $( '#contest-members-page' )
      @load_members()

      @member_images_view = new MemberImagesView( @members ) unless @member_images_view
      @pagination_view = new PaginationView() unless @pagination_view
    events             :
      'click .vote'         : 'handle_vote_click'
      'contest.vote'        : 'handle_vote'
      'contest.switch-page' : 'handle_switch_page'
    load_members       : ->
      $.ajax
        url      : @script_element.data 'url'
        dataType : 'json'
        async    : false
        success  : ( r ) =>
          @allow_vote = r.allow_vote
          @members = new Members r.members
    render             : ( id ) ->
      @member_images_view.render( @allow_vote, id )
      @$el.html @template members : @get_members(), allow_vote : @allow_vote
      @render_pagination()
      window.addthis.toolbox( '.addthis-list' )
      @$el
    render_pagination  : ->
      @pagination_view.render @page, Math.floor( @members.toJSON().length / @per_page ) + 1
    get_members        : ->
      @page = 1 if (@page - 1) * @per_page > @members.length
      offset = (@page - 1) * @per_page
      @members.toJSON().slice( offset, offset + @per_page )
    handle_vote_click  : ( e ) ->
      $b = $ e.target
      id = $b.data 'member-id'
      @$el.trigger 'contest.vote', { member : @members.get id }
    handle_vote        : ( e, data ) ->
      console.log e, data
      member = data.member
      $.ajax
        url      : Routing.generate 'contest.vote', { slug : @contest, id : member.id }
        type     : 'PUT'
        complete : =>
          @load_members()
          @render()
    handle_switch_page : ( e, page )->
      @page = parseInt(page)
      console.log page
      @render()