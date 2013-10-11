@MemberImagesView = Backbone.View.extend
  template          : _.template $( '#member-view-template' ).html()
  members           : null
  voffset           : 40
  max_height        : 800
  holder            : null
  resize_timeout_id : null
  initialize        : ( members ) ->
    @members = members
    @$el.addClass 'member-view hidden'
    @$el.appendTo $( 'body' )
    $( window ).on 'resize', =>
      clearTimeout( @resize_timeout_id ) if @resize_timeout_id
      @resize_timeout_id = setTimeout( =>
        @update_height()
      , 500 )
  events            :
    'click .close-modal' : 'close'
    'click .locker'      : 'close'
    'click .vote'        : 'handle_vote_click'
  render            : ( allow_vote, slug ) ->
    if slug
      member = @members.get slug
      @$el.html @template member : member.toJSON(), allow_vote : allow_vote
      @holder = $( '.view-holder', @$el )
      @open()
      $( '.scrollable', @$el ).scrollable circular : false
    else
      @close() if @$el.is ':visible'
    @$el

  close         : ->
    @$el.addClass 'hidden'
    @holder = null
    location.hash = ''
    clearInterval( @resize_interval_id ) if @resize_interval_id
  open          : ->
    @$el.removeClass 'hidden'
    @update_height()
    window.addthis.toolbox( '.addthis-member' )
  update_height : ->
    return false unless @holder
    wh = $( window ).height()
    h = Math.min wh - (@voffset * 2), @max_height
    $( '.images-list img', @$el ).height h
    @holder.height( h ).css marginTop : -h / 2

  handle_vote_click : ( e ) ->
    $b = $ e.target
    id = $b.data 'member-id'
    $( '.members-list-view' ).trigger 'contest.vote', member : @members.get id