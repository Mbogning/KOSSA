import React from 'react';
import Base from '../../components/news/Base';
import ContentRightMidle from '../../components/news/ContentRightMidle';
import axios from 'axios';
import TimeAgo from 'react-timeago'
import frenchStrings from 'react-timeago/lib/language-strings/fr'
import englishStrings from 'react-timeago/lib/language-strings/en'
import buildFormatter from 'react-timeago/lib/formatters/buildFormatter'
import InfiniteScroll from 'react-infinite-scroller';
import renderHTML from 'react-render-html';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { NavLink } from 'react-router-dom';
import * as constant from '../Constant';

class DetailGenre extends React.Component {
  constructor(props) {
    super(props);
    this.number = 10; //le nombre de commentaires a charger
    this.state = {
      genre: null,
      vues: 0,
      jaimes: 0,
      jaimepas: 0,
      comentaires: 0,
      favoris: 0,
      jaimeClass: false,
      jaimePasClass: false,
      content: '',
      comments: [],
      hasMoreItems: true,
    };

    this.handleContentChange = this.handleContentChange.bind(this);

  }

  componentDidMount() {

    const { match: { params } } = this.props;

    axios.get(`/${window.locale}/news/json_genre/${params.genreSlug}`)
      .then(response => {
        console.log('genre....', response.data)
        this.setState({
          genre: response.data,
          vues: response.data.vues,
          jaimes: response.data.jaime,
          jaimepas: response.data.jaimepas,
          comentaires: response.data.comments.length,
          favoris: response.data.user_favoris.length
        });
      })
      .catch(error => console.log(error));

    //on voit si le user a deja aimé
    fetch('/' + window.locale + '/news/json_genreaime/' + params.genreSlug)
      .then(response => response.json())
      .then(entries => {
        this.setState({
          jaimeClass: entries,
        });
      });

    fetch('/' + window.locale + '/news/json_genreaimepas/' + params.genreSlug)
      .then(response => response.json())
      .then(entries => {
        this.setState({
          jaimePasClass: entries,
        });
      });

    fetch('/' + window.locale + '/news/json_genrefavoris/' + params.genreSlug)
      .then(response => response.json())
      .then(entries => {
        console.log('favoris', entries)

        this.setState({
          favorisClass: entries,
        });
      });
  }


  handleJaimesClick() {
    if (!this.state.jaimeClass) {
      if (this.state.jaimePasClass) {
        this.setState({
          jaimePasClass: false,
          jaimepas: this.state.jaimepas - 1,
        });
      }
    }

    this.setState({
      jaimeClass: !this.state.jaimeClass,
    });


    fetch('/' + window.locale + '/news/json_managegenrejaime/' + this.state.genre.id)
      .then(response => response.json())
      .then(() => {
        if (this.state.jaimeClass) {
          this.setState({
            jaimes: this.state.jaimes + 1,
          });
        } else {
          this.setState({
            jaimes: this.state.jaimes - 1,
          });
        }
      });
  }

  handleJaimePasClick() {
    if (!this.state.jaimePasClass) {
      if (this.state.jaimeClass) {
        this.setState({
          jaimeClass: false,
          jaimes: this.state.jaimes - 1,
        });
      }
    }

    this.setState({
      jaimePasClass: !this.state.jaimePasClass,
    });


    fetch('/' + window.locale + '/news/json_managegenrejaimepas/' + this.state.genre.id)
      .then(response => response.json())
      .then(() => {
        if (this.state.jaimePasClass) {
          this.setState({
            jaimepas: this.state.jaimepas + 1,
          });
        } else {
          this.setState({
            jaimepas: this.state.jaimepas - 1,
          });
        }
      });
  }

  handleLoadMore(page) {
    let offset = (page - 1) * this.number;
    axios.get('/' + window.locale + '/news/json_genrecomment/' + this.state.genre.id + '/' + offset + '/' + this.number)
      .then(response => {
        console.log(response.data)
        if (response.data.length < this.number) {
          this.setState({
            hasMoreItems: false
          });
        }
        this.setState({
          comments: [...new Set(this.state.comments.concat(response.data))]
        });
      })
      .catch(error => console.log(error));
  }


  handlePublierClick() {
    let content = this.state.content;
    if (!content) {
      return;
    }

    axios.post('/' + window.locale + '/news/json_genrecommentpublier/' + this.state.genre.id, {
      comment: content
    }).then(response => {
      let comments = this.state.comments;
      comments.unshift(response.data)
      console.log(response.data)
      this.setState({
        comments: comments,
      });
      this.setState({ content: '' });
    }).catch(error => console.log(error));
  }

  handleContentChange(event) {
    this.setState({ content: event.target.value });
  }

  handleFavorisClick() {
    this.setState({
      favorisClass: !this.state.favorisClass,
    });
    fetch('/' + window.locale + '/news/json_managegenrefavoris/' + this.state.genre.id)
      .then(response => response.json())
      .then(entries => {
        console.log('favoris clik', entries)

        this.setState({
          vues: entries.vues,
          jaimes: entries.jaime,
          jaimepas: entries.jaimepas,
          comentaires: entries.comments.length,
          favoris: entries.user_favoris.length
        });
      });
  }

  onLinkClick(link) {
    window.parent.location.href = link;
  }


  render() {

    let formatter
    if (window.locale === 'fr') {
      formatter = buildFormatter(frenchStrings)
    } else {
      formatter = buildFormatter(englishStrings)
    }

    return (
      <Base
        contentRight={<ContentRightMidle />}
        content={

          <div>
            {this.state.genre != null &&
              <div>

                <div className="h3-responsive  font-weight-bold mt-1 mb-4 ">Genres musicaux</div>

                <div className="mb-4" id="makossa">
                  <div className="p-3 white brd-bt-news toile-content">
                    <div className="h6-responsive font-weight-bold text-uppercase ls3">
                      {this.state.genre.nom}
                    </div>
                  </div>
                  <div className="actu-news-img brd-bt-news position-relative" style={{ backgroundImage: `url(${this.state.genre.photo_url})` }}>
                    <div className="couv position-absolute h-100 w-100"></div>
                  </div>
                  <div className="pt-4 pl-4 pr-4 pb-2 white toile-content  mb-2 ls3">

                    <div className='mb-2 pb-2 trait-btm'>
                      <div className="d-inline-block text-center mr-4 mt-2">
                        <span className="d-inline-block fs-20" style={{ color: "#007bff" }} >{this.state.vues}</span><br />
                        <span className="fs-12 text-muted" >Vues</span>
                      </div>
                      <a onClick={() => this.handleJaimesClick()} className="d-inline-block text-center mr-4 mt-2 tt">
                        <FontAwesomeIcon style={this.state.jaimeClass ? { color: "#007bff" } : { color: "" }} className="fs-20" icon={this.state.jaimeClass ? ['fas', 'heart'] : ['far', 'heart']} />
                        <br /><span className="fs-12 text-muted" >{this.state.jaimes} j'aime</span>
                      </a>
                      <a onClick={() => this.handleJaimePasClick()} className="d-inline-block text-center mr-4 mt-2 tt">
                        <FontAwesomeIcon style={this.state.jaimePasClass ? { color: "#007bff" } : { color: "" }} className="fs-20" icon={this.state.jaimePasClass ? ['fas', 'thumbs-down'] : ['far', 'thumbs-down']} />
                        <br /> <span className="fs-12 text-muted" >{this.state.jaimepas} j'aime pas</span>
                      </a>
                      <a href="#comment" className="d-inline-block text-center mr-4 mt-2 tt">
                        <i className="far fa-comment fs-20"></i><br />
                        <span className="fs-12 text-muted" >{this.state.comentaires} Commentaires</span>
                      </a>
                      <a onClick={this.handleFavorisClick.bind(this)} className="d-inline-block text-center mr-4 mt-2 tt">
                        <FontAwesomeIcon style={this.state.favorisClass ? { color: "#007bff" } : { color: "" }} className="fs-20" icon={this.state.favorisClass ? ['fas', 'star'] : ['far', 'star']} />
                        <br />
                        <span className="fs-12 text-muted" >{this.state.favoris} Favoris</span>
                      </a>

                      <div className="d-inline-block text-center position-relative zit">
                        <div className='d-none position-absolute artiste-share  p-2 rounded-pill z-depth-2 bg-news' style={{ width: '260px', top: '10%', left: '-150%' }}>
                          <div className="">
                            <a href="ll" className="btn-social fb-ic mr-3" role="button"><i className="fab fa-lg fa-facebook-f"></i></a><a href="ll" className="btn-social tw-ic mr-3" role="button"><i className="fab fa-lg fa-twitter"></i></a><a href="ll" className="btn-social gplus-ic mr-3" role="button"><i className="fab fa-lg fa-google-plus-g"></i></a><a href="ll" className="btn-social li-ic mr-3" role="button"><i className="fab fa-lg fa-linkedin-in"></i></a><a href="ll" className="btn-social li-ic mr-3" role="button"><i className="fab fa-lg  fa-facebook-messenger"></i></a><a href="ll" className="btn-social ins-ic mr-3" role="button"><i className="fab fa-lg fa-instagram"></i></a><a href="ll" className="btn-social email-ic" role="button"><i className="far fa-lg fa-envelope"></i></a>
                          </div>
                        </div>
                        <span className="d-inline-block text-center mr-4 mt-2 tt btn-artiste-share tt cur-pointer">
                          <i className="fa fa-share fs-20"></i><br />
                          <span className="fs-12 text-muted" >partager</span>
                        </span>
                      </div>
                    </div>

                    <div className="trait-btm pb-2" style={{ color: 'black', fontSize: 'medium', fontFamily: 'Arial' }}>
                      {renderHTML(this.state.genre.description)}
                    </div>
                    <div className="mb-1 mt-2 text-right">
                      <span className="">
                        <NavLink onClick={() => this.onLinkClick(`${constant.PLAY_URL}/artist/genre/${this.state.genre.slug}`)} to={`${constant.PLAY_URL}/artist/genre/${this.state.genre.slug}`} className="font-weight-bold  fs-12 btn-sm  tt">
                          <i className="fa fa-star mr-1"></i> Voir les artistes
                        </NavLink>
                      </span>
                    </div>
                  </div>
                </div>

                {this.state.genre.link == 1 &&
                  <div className="pl-4 pt-4 pr-4 pb-3 white toile-content mb-4">
                    <div className="h6-responsive font-weight-bold text-muted mb-2">
                      Laissez un commentaire
   </div>
                    <div>
                      <form action="">
                        <textarea value={this.state.content}
                          name="comment" id="" cols="30" className="w-100 p-3"
                          placeholder="Rédigez ici" style={{ minHeight: '150px' }}
                          onChange={this.handleContentChange}
                          required
                        >
                        </textarea>
                      </form>
                    </div>
                    <div className="text-right" >
                      <button onClick={() => this.handlePublierClick()} type="button" className='btn btn-primary position-relative  btn-sm p-1 pl-3 pr-3 font-weight-bold mr-0 btn-news'><i className="fa fa-share mr-2"></i> Publier
</button>
                    </div>
                  </div>
                }

                <div className="pl-4 pt-4 pr-4 pb-3 white toile-content mb-4" id="comment">
                  <div className="mb-4 fs-12"><i className="fa fa-comment ml-2 text-muted "></i> {this.state.comments.length} Commentaires</div>

                  <InfiniteScroll
                    pageStart={0}
                    loadMore={this.handleLoadMore.bind(this)}
                    hasMore={this.state.hasMoreItems}
                    loader={<div className="loader" key={0}>Chargement des commentaires...</div>}
                  >
                    {this.state.comments.map(
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
            }

          </div>

        }
      >
      </Base>
    );
  }
}

export default DetailGenre;



















