import React from 'react';
import { NavLink } from 'react-router-dom';
import { Trans } from 'react-i18next';
import * as constant from '../Constant';

class MenuLeft extends React.Component {

    onLinkClick(link) {
        window.parent.location.href = link;
    }

    render() {
        const className = 'btn btn-rounded  btn-sm font-weight-bold hv-wh-50 btn-news';
        const activeClassName = 'btn btn-rounded btn-sm font-weight-bold hv-wh-50 btn-outline-purple';

        return (
            <div>
                <a className={constant.NEWS_URL  == window.location.pathname ? activeClassName : className} href={constant.NEWS_URL + '/'}>
                    <Trans  >
                        Actualités
                    </Trans>
                </a>
                <NavLink className={window.location.pathname.includes('/news/genre') ? activeClassName : className} to={constant.NEWS_URL + '/genre'}>Genres</NavLink>
                <a href={constant.PLAY_URL + '/artist'} className={window.location.pathname.includes('/play/artist') ? activeClassName : className} >Artistes</a>
                <a href={constant.PLAY_URL + '/top10'} className={window.location.pathname.includes('/play/top10') ? activeClassName : className} >TOP 10</a>
                <a href={constant.PLAY_URL + '/playlist'} className={window.location.pathname.includes('/play/playlist') ? activeClassName : className} >MA PLAYLIST</a>
                <a href={constant.PLAY_URL + '/jaime'} className={window.location.pathname.includes('/play/jaime') ? activeClassName : className} >MES J'AIMES</a>

            </div>
        );

    }
}

export default MenuLeft;


















