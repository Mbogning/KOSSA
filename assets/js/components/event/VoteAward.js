import React, { InvalidEvent } from 'react';
import Comments from '../../components/Comments';
import axios from 'axios';



class VoteAward extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            nom: '',
            email: '',
            idartiste: null,
            pseudoartiste: '',
            votes: {},
            categories: [],
            errors: {
                name: null,
                email: null,
            }
        };

        this.handleVoteClick = this.handleVoteClick.bind(this);
        this.handleVoteSubmit = this.handleVoteSubmit.bind(this);
    }

    componentDidMount() {
        fetch('/' + window.locale + '/event/json_awardcategorie/' + window.eventEventId)
            .then(response => response.json())
            .then(entries => {
                //console.log('categories...', entries)
                this.setState({
                    categories: entries,
                });
            });

    }

    handleVoteSubmit(e) {

        console.log('nianag....................')
        const errors = this.validate(this.state.nom, this.state.email);
        if (errors) {
            // do something with errors
            return;
        }
        axios.post('/' + window.locale + '/event/json_guestvote/' + this.state.idartiste, {
            email: this.state.email,
            nom: this.state.nom
        }).then(response => {
            let votes = this.state.votes;
            votes[this.state.idartiste] = true;
            this.setState({ votes: votes });
            $('.b-tkt').removeClass('d-none b-tkt').addClass('fadeIn');
            $('.cont-addrvote').removeClass('siut').addClass('d-none');
            $('.cont-all-plst').append('<div class="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i class="fa fa-thumbs-up mr-2 text-success"></i> Merci d\'avoir voté ' + this.state.pseudoartiste + ' . <br><span class="fs-12 text-white-50">NB: Vous ne pourrez plus voter dans cette catégorie.</span><div class="text-right"> <button type="button" class="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }).catch(error => {
            $('.b-tkt').removeClass('d-none b-tkt').addClass('fadeIn');
            $('.cont-addrvote').removeClass('siut').addClass('d-none');
            $('.cont-all-plst').append('<div class="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i class="fa fa-times mr-2 text-danger"></i> Vous avez deja voté dans cette catégorie. <br><span class="fs-12 text-white-50">NB: Vous ne pouvez voter qu\'une seule fois dans cette catégorie.</span><div class="text-right"> <button type="button" class="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
        }
        );
    }

    handleContentChange(e) {
        const name = e.target.name;
        const value = e.target.value;
        this.setState({ [name]: value });
    }

    handleVoteClick(idartiste, pseudoartiste) {
        this.setState({ idartiste: idartiste });
        this.setState({ pseudoartiste: pseudoartiste });
        if (window.userId == 0) {//invite
            $('.cont-addrvote').removeClass('d-none').addClass('siut');
        } else {
            //console.log('login...')
            axios.post('/' + window.locale + '/event/json_uservote/' + idartiste, {
            }).then(response => {
                let votes = this.state.votes;
                votes[idartiste] = true;
                this.setState({ votes: votes });
                //console.log('votes...',this.state.votes)
                $('.cont-all-plst').append('<div class="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i class="fa fa-thumbs-up mr-2 text-success"></i> Merci d\'avoir voté ' + pseudoartiste + ' . <br><span class="fs-12 text-white-50">NB: Vous ne pourrez plus voter dans cette catégorie.</span><div class="text-right"> <button type="button" class="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');

            }).catch(error => {
                $('.cont-all-plst').append('<div class="black info-vote p-lst p-3 z-depth-3 position-fixed animated text-right text-white ls3 fadeInUp dr-03"><i class="fa fa-times mr-2 text-danger"></i> Vous avez deja voté dans cette catégorie. <br><span class="fs-12 text-white-50">NB: Vous ne pouvez voter qu\'une seule fois dans cette catégorie.</span><div class="text-right"> <button type="button" class="btn btn-outline-success btn-sm ok-vote">ok</button> </div></div>');
            }
            );
        }

    }

    handleVoteClose(e) {
        e.preventDefault();
        e.stopPropagation();
        $('.b-tkt').removeClass('d-none b-tkt').addClass('fadeIn');
        $('.cont-addrvote').removeClass('siut').addClass('d-none');
    }

    validate(name, email) {
        let token = null;
        let errors = {};
        if (name.length === 0) {
            errors.name = "Le nom ne doit pas etre vide";
            token="errors";
        }

        if (!(/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$/.test(email))) {
            errors.email = "Le mail n'est pas valide";
            token="errors";
        }
        this.setState({ errors: errors });
        return token;
    }


    render() {

        return (
            <div >
                {this.state.categories.map(
                    ({ id, artistes, nom }) => (
                        <div key={id}>
                            <div className="h6-responsive font-weight-bold mt-3 mb-1 text-center text-md-left">
                                {nom}
                            </div>
                            <div className="pl-4 pt-4 pr-4 pb-2 white toile-content ">
                                {artistes.map(
                                    ({ id, artiste, hasvoted }) => (
                                        <div key={id} className="d-inline-block mb-3 ml-2 mt-3" >
                                            <div className="position-relative d-inline-block ">
                                                <div className="content-mus-v rounded-pill brd-bl position-relative ovh" style={{ backgroundImage: `url(${artiste.photo_url})` }}>
                                                    <div className="position-absolute couv tp-lf0 h-100 w-100"></div>
                                                </div>
                                                <div className="mt-1 name-mus weight-mus text-center">
                                                    <div className="font-weight-bold name-art text-center">{artiste.pseudo}</div>
                                                    {(hasvoted || this.state.votes[id] === true) ?
                                                        <div className="text-center "><a className="text-center btn btn-outline-blue-grey font-weight-bold ls3 btn-sm btn-vote">Vous avez voté</a></div>
                                                        :
                                                        <div className="text-center "><a onClick={() => this.handleVoteClick(id, artiste.pseudo)} className="text-center btn btn-outline-blue-grey font-weight-bold ls3 btn-sm btn-vote">Je vote</a></div>
                                                    }
                                                </div>
                                            </div>
                                        </div>
                                    )
                                )}
                            </div>

                        </div>
                    )
                )}

                <div className="position-fixed h-100 w-100 bg-event-pop cont-addrvote d-none zitp animated" style={{ top: '0px', left: '0px' }}>
                    <div className="h-100 w-100 d-flex justify-content-center align-items-center ">
                        <div className=" white p-4 z-depth-1">
                            <div className="position-relative p-3 rounded-bottom">
                                <div onClick={this.handleVoteClose.bind(this)} className="position-absolute pl-2 pr-2 roud-close pt-1 pb-1 bnt-closett cur-pointer " style={{ top: '0px', right: '0px' }}>
                                    <i className="fa fa-plus rot-90 fs-12 "></i>
                                </div>
                                <div className="text-center">
                                    <span ><img src="/kossa/img/kossa.png" className="mascot3" alt="" /></span>
                                </div>
                                <div className="mt-1 text-black-50 fs-16 text-center mb-4">Vote </div>
                                    <div className="input-group mb-2">
                                        <label htmlFor="nom" className=" mb-0 ls3 fs-12">Nom et prénom*
                                        {this.state.errors.name != null && <div style={{ color: 'red' }}> {this.state.errors.name}</div>}
                                        </label>
                                        <input required value={this.state.nom} onChange={this.handleContentChange.bind(this)} type="tel" id="nom" name="nom" className="w-100 p-2 mt-0 trs3  inp-tk" placeholder="Entrez votre nom" />
                                    </div>
                                    <div className="input-group mb-2 ">
                                        <label htmlFor="mail" className=" mb-0 ls3 fs-12">Addrese mail*
                                        {this.state.errors.email != null && <span style={{ color: 'red' }}> {this.state.errors.email}</span>}
                                        </label>
                                        <input required type='email' value={this.state.email} onChange={this.handleContentChange.bind(this)} type="email" id="mail" name="email" className="w-100 p-2 mt-0 trs3 " placeholder="Entrez votre addrese mail" />
                                    </div>
                                    <div className="input-group mt-4 p-0 ">
                                        <button type='button' onClick={this.handleVoteSubmit} className="btn btn-purple btn-sm btn-rounded font-weight-bold ml-0 mr-0 ">Valider le vote</button>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>





            </div>
        );
    }
}

export default VoteAward;


