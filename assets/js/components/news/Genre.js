import React from 'react';
import Banner from '../../components/news/Banner';
import Base from '../../components/news/Base';
import ContentRightMidle from '../../components/news/ContentRightMidle';
import axios from 'axios';
import Truncate from 'react-truncate-html';
import { NavLink } from 'react-router-dom';
import * as constant from '../Constant';


class Genre extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            types: [],
        };
    }

    componentDidMount() {

        axios.get('/' + window.locale + '/news/json_genres')
            .then(response => {
                console.log('ccccccccccccc', response.data)
                this.setState({
                    types: response.data
                });
            })
            .catch(error => console.log(error));
    }

    onLinkClick(link) {
        window.parent.location.href = link;
    }


    render() {
        return (
            <Base
                banner={<Banner />}
                contentRight={<ContentRightMidle />}
                content={
                    <div >
                        <div className="h3-responsive  font-weight-bold mt-1 mb-4 ">Genres musicaux</div>
                        <div className="mb-4" >
                            <div className="p-3 white  toile-content">
                                {this.state.types.map(
                                    ({ id, nom, genre_musicals }) => (
                                        <div key={id}>
                                            <div className="trait-btm">
                                                <div className="badge badge-dark font-weight-bold text-uppercase ls3">
                                                    {nom}
                                                </div>
                                            </div>
                                            <div className="row">
                                                {genre_musicals.map(
                                                    ({ id, slug, nom }) => (
                                                        <a key={id} href={'#' + slug} className="h4-responsive font-weight-bold tt col-md-4 p-3">#{nom}</a>
                                                    )
                                                )}

                                            </div>
                                        </div>
                                    )
                                )}
                            </div>
                        </div>

                        {this.state.types.map(
                            ({ id, genre_musicals }) => (
                                <div key={id}>
                                    {genre_musicals.map(
                                        ({ id, nom, slug, description, photo_url }) => (
                                            <div key={id} className="mb-4" id={slug}>
                                                <div className="p-3 white brd-bt-news toile-content">
                                                    <div className="h6-responsive font-weight-bold text-uppercase ls3">
                                                        {nom}
                                                    </div>
                                                </div>
                                                <div className="actu-news-img brd-bt-news position-relative" style={{ backgroundImage: `url(${photo_url})` }}>
                                                    <div className="couv position-absolute h-100 w-100"></div>
                                                </div>
                                                <div className="pt-4 pl-4 pr-4 pb-2 white toile-content  mb-4 ls3">
                                                    <div className="trait-btm pb-2" style={{ fontSize: 'medium', fontFamily: 'Arial' }}>
                                                        <Truncate
                                                            lines={4}
                                                            dangerouslySetInnerHTML={{
                                                                __html: description
                                                            }}
                                                        />

                                                    </div>
                                                    <div className="mb-1 mt-2 text-right">
                                                        <span className="mr-1">
                                                            <NavLink to={`${constant.NEWS_URL}/genre/${slug}`} className="font-weight-bold  fs-12 btn-sm tt">
                                                                <i className="fa fa-plus mr-1"></i> Plus de détails
                                                        </NavLink>
                                                        </span>
                                                        <span className="">
                                                            <NavLink  onClick={() => this.onLinkClick(`${constant.PLAY_URL}/artist/genre/${slug}`)} to={`${constant.PLAY_URL}/artist/genre/${slug}`} className="font-weight-bold  fs-12 btn-sm  tt">
                                                                <i className="fa fa-star mr-1"></i> Voir les artistes
                                                        </NavLink>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        )
                                    )}

                                </div>
                            )
                        )}

                    </div>
                }
            >
            </Base>
        );
    }
}

export default Genre;




















