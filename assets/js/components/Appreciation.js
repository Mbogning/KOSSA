import React from 'react';
//import '../../../public/kossa/css/app.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';


class Appreciation extends React.Component {

  render() {

    return (

      <div className='mb-2 pb-2 trait-btm'>
        <div className="d-inline-block text-center mr-4 mt-2">
          <span className="d-inline-block fs-20" style={{ color: "#007bff" }} >{this.props.vues}</span><br />
          <span className="fs-12 text-muted" >Vues</span>
        </div>
        <a onClick={this.props.onJaimesClick} className="d-inline-block text-center mr-4 mt-2 tt">
          <FontAwesomeIcon style={this.props.jaimeClass ? { color: "#007bff" } : { color: "" }} className="fs-20" icon={this.props.jaimeClass ? ['fas', 'heart'] : ['far', 'heart']} />
          <br /><span className="fs-12 text-muted" >{this.props.jaimes} j'aime</span>
        </a>
        <a onClick={this.props.onJaimePasClick} className="d-inline-block text-center mr-4 mt-2 tt">
          <FontAwesomeIcon style={this.props.jaimePasClass ? { color: "#007bff" } : { color: "" }} className="fs-20" icon={this.props.jaimePasClass ? ['fas', 'thumbs-down'] : ['far', 'thumbs-down']} />
          <br /> <span className="fs-12 text-muted" >{this.props.jaimepas} j'aime pas</span>
        </a>
        <a href="#comment" className="d-inline-block text-center mr-4 mt-2 tt">
          <i className="far fa-comment fs-20"></i><br />
          <span className="fs-12 text-muted" >{this.props.comentaires} Commentaires</span>
        </a>

        {this.props.hasfavoris===true &&
          <a onClick={this.props.onFavorisClick} className="d-inline-block text-center mr-4 mt-2 tt">
            <FontAwesomeIcon style={this.props.favorisClass ? { color: "#007bff" } : { color: "" }} className="fs-20" icon={this.props.favorisClass ? ['fas', 'star'] : ['far', 'star']} />
            <br />
            <span className="fs-12 text-muted" >{this.props.favoris} Favoris</span>
          </a>
        }

        <div className="d-inline-block text-center position-relative zit">
          <div className={'d-none position-absolute artiste-share  p-2 rounded-pill z-depth-2 bg-' + this.props.color} style={{ width: '260px', top: '10%', left: '-150%' }}>
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
    );
  }
}

export default Appreciation;
