import React from 'react';
import TimeAgo from 'react-timeago'
import frenchStrings from 'react-timeago/lib/language-strings/fr'
import englishStrings from 'react-timeago/lib/language-strings/en'
import buildFormatter from 'react-timeago/lib/formatters/buildFormatter'
import InfiniteScroll from 'react-infinite-scroller';


class Comments extends React.Component {

  render() {
    let formatter
    if (this.props.locale === 'fr') {
      formatter = buildFormatter(frenchStrings)
    } else {
      formatter = buildFormatter(englishStrings)
    }
    return (
      <div>
         {this.props.login==1 &&
        <div className="pl-4 pt-4 pr-4 pb-3 white toile-content mb-4">
          <div className="h6-responsive font-weight-bold text-muted mb-2">
            Laissez un commentaire
              </div>
          <div>
            <form action="">
              <textarea value={this.props.content}
                name="comment" id="" cols="30" className="w-100 p-3"
                placeholder="Rédigez ici" style={{ minHeight: '150px' }}
                onChange={this.props.onContentChange}
                required
              >
              </textarea>
            </form>
          </div>
          <div className="text-right" >
            <button onClick={this.props.onPublierClick} type="button" className={'btn btn-primary position-relative  btn-sm p-1 pl-3 pr-3 font-weight-bold mr-0 btn-' + this.props.color}><i className="fa fa-share mr-2"></i> Publier
                  </button>
          </div>
        </div>
        }

        <div className="pl-4 pt-4 pr-4 pb-3 white toile-content mb-4" id="comment">
          <div className="mb-4 fs-12"><i className="fa fa-comment ml-2 text-muted "></i> {this.props.comments.length} Commentaires</div>

          <InfiniteScroll
            pageStart={0}
            loadMore={this.props.loadFunc}
            hasMore={this.props.hasMoreItems}
            loader={<div className="loader" key={0}>Chargement des commentaires...</div>}
          >
            {this.props.comments.map(
              ({ id, content, published_at, author, author_photo_url }) => (
                <div key={id} className="row mt-3">
                  <div className="col-3 col-md-2 pr-0">
                    <div className="img-photo-com mb-1 z-depth-1 content-mus rounded-pill" style={{ backgroundImage: `url(${author_photo_url})` }}>
                    </div>
                  </div>
                  <div className="col-12 col-md-10 pl-1 pt-2 trait-btm pb-4 fs-13 ls3 lh-16 white">
                    <div className="mb-2 ">
                      <div className="font-weight-bold fs-13"> {author.nom} <span className="fs-12 text-right cl-n2 font-weight-normal">
                        <TimeAgo date={published_at} formatter={formatter} />
                      </span></div>
                    </div>
                    {content}
                  </div>
                </div>
              )
            )}
          </InfiniteScroll>

        </div>
      </div>
    );
  }
}

export default Comments;
